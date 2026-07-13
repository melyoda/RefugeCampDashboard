<?php

namespace App\Controllers;

use App\Models\ResidentModel;
use App\Models\FamilyMemberModel;
use CodeIgniter\Controller;

class RegisterController extends Controller
{
    public function index()
    {
        return view('users/register');
    }

    public function save()
    {
        $rules = [
            'first_name'    => 'required|min_length[2]|max_length[100]',
            'last_name'     => 'required|min_length[2]|max_length[100]',
            'document_id'   => 'required|min_length[3]|max_length[100]|is_unique[residents.document_id]',
            'primary_phone' => 'required|min_length[7]|max_length[20]',
            'dob'           => 'required|valid_date[Y-m-d]',
            'marital_status'=> 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 1. Initialize our models
        $residentModel = new ResidentModel();
        $familyModel   = new FamilyMemberModel();

        // 2. Generate a secure, human-readable access code
        $plainAccessCode = $this->generateSecureAccessCode();

        // 3. Count dependents dynamically from the form payload array
        $incomingMembers = $this->request->getPost('members') ?: [];
        $childrenCount   = 0;

        foreach ($incomingMembers as $m) {
            if (($m['relationship_type'] ?? '') === 'Child') {
                $childrenCount++;
            }
        }

        // 4. Save the Primary Resident Profile (Forces status to pending by setting is_active = 0)
        $residentData = [
            'document_id'        => $this->request->getPost('document_id'),
            'access_code_hash'   => password_hash($plainAccessCode, PASSWORD_BCRYPT),
            'first_name'         => $this->request->getPost('first_name'),
            'last_name'          => $this->request->getPost('last_name'),
            'full_name'          => trim($this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name')),
            'dob'                => $this->request->getPost('dob'),
            'primary_phone'      => $this->request->getPost('primary_phone'),
            'backup_phone'       => $this->request->getPost('backup_phone') ?: null,
            'marital_status'     => $this->request->getPost('marital_status'),
            'children_count'     => $childrenCount,
            'has_disability'     => $this->request->getPost('has_disability') ?? 0,
            'disability_details' => $this->request->getPost('disability_details') ?: null,
            'is_active'          => 0 // 0 = Pending Triage Status
        ];

        $residentModel->save($residentData);
        $residentId = $residentModel->getInsertID(); // Grab the generated ID for Foreign Key mapping

        // 5. Loop through and save individual family members to the sub-table
        if (!empty($incomingMembers) && $residentId) {
            foreach ($incomingMembers as $member) {
                // Safeguard against empty dynamic rows
                if (empty($member['full_name'])) continue;

                $familyModel->save([
                    'resident_id'        => $residentId,
                    'relationship_type'  => $member['relationship_type'],
                    'full_name'          => $member['full_name'],
                    'dob'                => $member['dob'],
                    'gender'             => $member['gender'],
                    'has_disability'     => $member['has_disability'] ?? 0,
                    'disability_details' => $member['disability_details'] ?: null,
                ]);
            }
        }

        // 6. Direct to a registration confirmation page displaying their generated credentials
        return view('users/registration_success', [
            'name' => $residentData['full_name'],
            'id'   => $residentData['document_id'],
            'code' => $plainAccessCode
        ]);
    }

    private function generateSecureAccessCode(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $chars[rand(0, strlen($chars) - 1)];
        }
        return substr($code, 0, 3) . '-' . substr($code, 3, 3);
    }
}