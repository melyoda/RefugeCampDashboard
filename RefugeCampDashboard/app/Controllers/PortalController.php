<?php

namespace App\Controllers;

use App\Models\ResidentModel;
use App\Models\FamilyMemberModel;
use CodeIgniter\Controller;

class PortalController extends Controller
{
    public function login()
    {
        // If they are already logged in as a resident, skip the login form
        if (session()->get('is_resident_logged_in')) {
            return redirect()->to('household/dashboard');
        }

        return view('portal_login');
    }

    public function auth()
    {
        $session = session();
        $model   = new ResidentModel();

        $documentId = $this->request->getPost('document_id');
        $accessCode = $this->request->getPost('access_code');

        // 1. Find the resident record by their registration document ID
        $resident = $model->where('document_id', $documentId)->first();

        if (!$resident) {
            return redirect()->back()->withInput()->with('error', 'Invalid Registration ID or Access Code.');
        }

        // 2. Security Check: Verify the plain text code against the database bcrypt hash
        if (!password_verify($accessCode, $resident['access_code_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid Registration ID or Access Code.');
        }

        // 3. Triage Status Check: Is this account still pending approval?
        if ((int)$resident['is_active'] === 0) {
            return redirect()->back()->withInput()->with('error', 'Your registration is currently pending review by camp administration. Please check back later.');
        }

        // 4. Set secure session flags (completely independent from Shield's admin sessions)
        $session->set([
            'resident_id'            => $resident['id'],
            'resident_name'          => $resident['full_name'],
            'is_resident_logged_in'  => true
        ]);

        return redirect()->to('household/dashboard');
    }

    public function dashboard()
    {
        $session = session();

        // Custom security guard check for this specific dashboard endpoint
        if (!$session->get('is_resident_logged_in')) {
            return redirect()->to('household/login')->with('error', 'Please log in to access your family profile.');
        }

        $residentModel = new ResidentModel();
        $familyModel   = new FamilyMemberModel();

        // Fetch data STRICTLY using the ID stored in the session state
        $residentId = $session->get('resident_id');

        $data['family_head'] = $residentModel->find($residentId);
        $data['dependents']  = $familyModel->where('resident_id', $residentId)->findAll();

        return view('portal_dashboard', $data);
    }

    public function addMember()
{
    $session = session();
    if (!$session->get('is_resident_logged_in')) {
        return redirect()->to('household/login');
    }

    $familyModel = new \App\Models\FamilyMemberModel();
    $residentId  = $session->get('resident_id'); // Pulled safely from session storage

    $familyModel->save([
        'resident_id'        => $residentId,
        'relationship_type'  => $this->request->getPost('relationship_type'),
        'full_name'          => $this->request->getPost('full_name'),
        'dob'                => $this->request->getPost('dob'),
        'gender'             => $this->request->getPost('gender'),
        'has_disability'     => $this->request->getPost('has_disability') ?? 0,
        'disability_details' => $this->request->getPost('disability_details') ?: null,
    ]);

    // Recalculate children count for the family head automatically
    $this->updateChildrenCount($residentId);

    return redirect()->to('household/dashboard')->with('success', 'Family member appended to roster.');
}

public function removeMember($id)
{
    $session = session();
    if (!$session->get('is_resident_logged_in')) {
        return redirect()->to('household/login');
    }

    $familyModel = new \App\Models\FamilyMemberModel();
    $residentId  = $session->get('resident_id');

    // Security Check: Verify that the dependent record belongs to the logged-in resident
    $member = $familyModel->where('id', $id)->where('resident_id', $residentId)->first();

    if ($member) {
        $familyModel->delete($id);
        $this->updateChildrenCount($residentId); // Recalculate child count counters
        return redirect()->to('household/dashboard')->with('success', 'Member removed from roster.');
    }

    return redirect()->to('household/dashboard')->with('error', 'Unauthorized roster change request blocked.');
}

private function updateChildrenCount($residentId)
{
    $residentModel = new \App\Models\ResidentModel();
    $familyModel   = new \App\Models\FamilyMemberModel();

    $childrenCount = $familyModel->where('resident_id', $residentId)->where('relationship_type', 'Child')->countAllResults();
    $residentModel->update($residentId, ['children_count' => $childrenCount]);
}

    public function logout()
    {
        session()->destroy();
        return redirect()->to('household/login');
    }
}