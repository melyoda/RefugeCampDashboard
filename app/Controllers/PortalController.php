<?php

namespace App\Controllers;

use App\Models\ResidentModel;
use App\Models\FamilyMemberModel;
use CodeIgniter\Controller;

class PortalController extends Controller
{
    public function login()
    {
        if (session()->get('is_resident_logged_in')) {
            return redirect()->to(base_url('r/dashboard'));
        }

        return view('users/portal_login');
    }

    public function auth()
    {
        $session = session();
        $model   = new ResidentModel();

        $documentId = trim((string) $this->request->getPost('document_id'));
        $accessCode = trim((string) $this->request->getPost('access_code'));

        if (empty($documentId) || empty($accessCode)) {
            return redirect()->back()->withInput()->with('error', 'Please enter both Registration ID and Access Code.');
        }

        // 1. Find resident by document ID
        $resident = $model->where('document_id', $documentId)->first();

        if (!$resident) {
            return redirect()->back()->withInput()->with('error', 'Invalid Registration ID or Access Code.');
        }

        // 2. Verify hashed access code
        if (!password_verify($accessCode, $resident['access_code_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid Registration ID or Access Code.');
        }

        // 3. Check active status
        if ((int)$resident['is_active'] === 0) {
            return redirect()->back()->withInput()->with('error', 'Your registration is currently pending review.');
        }

        // 4. Save session data
        $session->set([
            'resident_id'           => $resident['id'],
            'resident_name'         => $resident['full_name'],
            'is_resident_logged_in' => true
        ]);

        return redirect()->to(base_url('r/dashboard'));
    }

    public function dashboard()
    {
        $session = session();

        if (!$session->get('is_resident_logged_in')) {
            return redirect()->to(base_url('r/login'))->with('error', 'Please log in to access your family profile.');
        }

        $residentModel = new ResidentModel();
        $familyModel   = new FamilyMemberModel();

        $residentId = $session->get('resident_id');

        $data['family_head'] = $residentModel->find($residentId);
        $data['dependents']  = $familyModel->where('resident_id', $residentId)->findAll();

        $db = \Config\Database::connect();
        $data['history'] = $db->table('activity_residents')
            ->join('activities', 'activities.id = activity_residents.activity_id')
            ->where('activity_residents.resident_id', $residentId)
            ->where('activities.deleted_at', null)
            ->orderBy('activity_residents.created_at', 'DESC')
            ->select('activities.title, activities.description, activities.aid_category, activities.is_distributed_aid, activity_residents.created_at')
            ->get()
            ->getResultArray();

        return view('users/portal_dashboard', $data);
    }

    public function addMember()
    {
        $session = session();
        if (!$session->get('is_resident_logged_in')) {
            return redirect()->to(base_url('r/login'));
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

        return redirect()->to(base_url('r/dashboard'))->with('success', 'Family member appended to roster.');
    }

    public function removeMember($id)
    {
        $session = session();
        if (!$session->get('is_resident_logged_in')) {
            return redirect()->to(base_url('r/login'));
        }

        $familyModel = new FamilyMemberModel();
        $residentId  = $session->get('resident_id');

        $member = $familyModel->where('id', $id)->where('resident_id', $residentId)->first();

        if ($member) {
            $familyModel->delete($id);
            $this->updateChildrenCount($residentId);
            return redirect()->to(base_url('r/dashboard'))->with('success', 'Member removed.');
        }

        return redirect()->to(base_url('r/dashboard'))->with('error', 'Unauthorized request.');
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
        return redirect()->to(base_url('r/login'));
    }
}