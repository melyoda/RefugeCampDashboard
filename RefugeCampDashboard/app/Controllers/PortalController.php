<?php

namespace App\Controllers;

use App\Models\ResidentModel;
use App\Models\FamilyMemberModel;
use CodeIgniter\Controller;

class PortalController extends Controller
{
    private string $baseRoute = 'household/';

    public function login()
    {
        if (session()->get('is_resident_logged_in')) {
            return redirect()->to($this->baseRoute . 'dashboard');
        }

        return view('users/portal_login');
    }

    public function auth()
    {
        $session = session();
        $model   = new ResidentModel();

        $documentId = $this->request->getPost('document_id');
        $accessCode = $this->request->getPost('access_code');

        // 1. Find the resident record by their registration document ID (e.g., National ID or Passport string)
        $resident = $model->where('document_id', $documentId)->first();

        if (!$resident) {
            return redirect()->back()->withInput()->with('error', 'Invalid Registration ID or Access Code.');
        }

        if (!password_verify($accessCode, $resident['access_code_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid Registration ID or Access Code.');
        }

        if ((int)$resident['is_active'] === 0) {
            return redirect()->back()->withInput()->with('error', 'Your registration is currently pending review.');
        }

        // 2. Fix: Store the actual primary key 'id' explicitly as 'resident_id'
        $session->set([
            'resident_id'           => $resident['id'], // This is the database auto-increment ID
            'resident_name'         => $resident['full_name'],
            'is_resident_logged_in' => true
        ]);

        return redirect()->to($this->baseRoute . 'dashboard');
    }

    // public function dashboard()
    // {
    //     $session = session();

    //     if (!$session->get('is_resident_logged_in')) {
    //         return redirect()->to($this->baseRoute . 'login')->with('error', 'Please log in.');
    //     }

    //     $residentModel = new ResidentModel();
    //     $familyModel   = new FamilyMemberModel();

    //     // 3. Retrieve the primary key from the session
    //     $residentId = $session->get('resident_id');

    //     $data['family_head'] = $residentModel->find($residentId);

    //     // 4. Querying family members using the correct foreign key column 'resident_id'
    //     $data['dependents']  = $familyModel->where('resident_id', $residentId)->findAll();

    //     return view('users/portal_dashboard', $data);
    // }

    public function dashboard()
    {
        $session = session();

        // Custom security guard check for this specific dashboard endpoint
        if (!$session->get('is_resident_logged_in')) {
            return redirect()->to($this->baseRoute . 'login')->with('error', 'Please log in to access your family profile.');
        }

        $residentModel = new ResidentModel();
        $familyModel   = new FamilyMemberModel();

        $residentId = $session->get('resident_id');

        $data['family_head'] = $residentModel->find($residentId);
        $data['dependents']  = $familyModel->where('resident_id', $residentId)->findAll();

        // fetch the distribution history for this logged-in resident
        $db = \Config\Database::connect();
        $data['history'] = $db->table('activity_residents')
            ->join('activities', 'activities.id = activity_residents.activity_id')
            ->where('activity_residents.resident_id', $residentId)
            ->where('activities.deleted_at', null) // Filter out soft-deleted activities
            ->orderBy('activity_residents.created_at', 'DESC') // Sort by when they actually received it
            ->select('activities.title, activities.description, activities.aid_category, activities.is_distributed_aid, activity_residents.created_at')
            ->get()
            ->getResultArray();

        return view('users/portal_dashboard', $data);
    }

    public function addMember()
    {
        $session = session();
        if (!$session->get('is_resident_logged_in')) {
            return redirect()->to($this->baseRoute . 'login');
        }

        $rules = [
            'relationship_type' => 'required',
            'full_name'         => 'required|min_length[3]|max_length[255]',
            'dob'               => 'required|valid_date[Y-m-d]',
            'gender'            => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please fill out all fields correctly.');
        }

        $familyModel = new FamilyMemberModel();
        $residentId  = $session->get('resident_id');

        // 5. Saving with 'resident_id' linking back to the head of household
        $familyModel->save([
            'resident_id'        => $residentId,
            'relationship_type'  => $this->request->getPost('relationship_type'),
            'full_name'          => $this->request->getPost('full_name'),
            'dob'                => $this->request->getPost('dob'),
            'gender'             => $this->request->getPost('gender'),
            'has_disability'     => $this->request->getPost('has_disability') ?? 0,
            'disability_details' => $this->request->getPost('disability_details') ?: null,
        ]);

        $this->updateChildrenCount($residentId);

        return redirect()->to($this->baseRoute . 'dashboard')->with('success', 'Family member appended to roster.');
    }

    public function removeMember($id)
    {
        $session = session();
        if (!$session->get('is_resident_logged_in')) {
            return redirect()->to($this->baseRoute . 'login');
        }

        $familyModel = new FamilyMemberModel();
        $residentId  = $session->get('resident_id');

        // 6. Checked against 'resident_id'
        $member = $familyModel->where('id', $id)->where('resident_id', $residentId)->first();

        if ($member) {
            $familyModel->delete($id);
            $this->updateChildrenCount($residentId);
            return redirect()->to($this->baseRoute . 'dashboard')->with('success', 'Member removed.');
        }

        return redirect()->to($this->baseRoute . 'dashboard')->with('error', 'Unauthorized request.');
    }

    private function updateChildrenCount($residentId)
    {
        $residentModel = new ResidentModel();
        $familyModel   = new FamilyMemberModel();

        $childrenCount = $familyModel->where('resident_id', $residentId)
                                     ->where('relationship_type', 'Child')
                                     ->countAllResults();

        $residentModel->update($residentId, ['children_count' => $childrenCount]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to($this->baseRoute . 'login');
    }
}