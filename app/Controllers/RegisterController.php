<?php

namespace App\Controllers;

use App\Models\ResidentModel;
use App\Models\FamilyMemberModel;
use CodeIgniter\Controller;

class RegisterController extends Controller
{
    // Class-wide properties for our models
    protected $residentModel;
    protected $familyModel;

    public function __construct()
    {
        // Initializing the models here cleans out noise from individual methods
        $this->residentModel = new ResidentModel();
        $this->familyModel   = new FamilyMemberModel();
    }

    /**
     * Renders the registration form view
     */
    public function index()
    {
        return view('users/register');
    }

    /**
     * Main action handling the registration form submission
     */
    public function save()
    {
        // 1. Run input format validation via helper
        if (!$this->validateRegistrationInput()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $incomingMembers = $this->request->getPost('members') ?: [];

        // 2. Tally dynamic members and check corporate business logic
        $counts = $this->tallyFamilyMembers($incomingMembers);
        if ($counts['spouses'] > 4) {
            return redirect()->back()->withInput()->with('errors', [
                'spouses' => 'عذراً، لا يمكن تسجيل أكثر من 4 زوجات للملف العائلي الواحد.'
            ]);
        }

        // 3. Generate credentials and write core head-of-household profile
        $plainAccessCode = $this->generateSecureAccessCode();
        $residentId = $this->savePrimaryResident($counts['children'], $plainAccessCode);

        // 4. Record individual dynamic sub-dependents mapping
        if ($residentId && !empty($incomingMembers)) {
            $this->saveFamilyMembers($residentId, $incomingMembers);
        }

        // 5. Send payload metadata straight to success landing
        return view('users/registration_success', [
            'name' => trim($this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name')),
            'id'   => $this->request->getPost('document_id'),
            'code' => $plainAccessCode
        ]);
    }

    /**
     * Enforces request validation rules, string sizes, and explicit regex matches
     */
    private function validateRegistrationInput(): bool
    {
        $rules = [
            'first_name'    => 'required|min_length[2]|max_length[100]',
            'last_name'     => 'required|min_length[2]|max_length[100]',
            'document_id'   => 'required|regex_match[/^[0-9]{9}$/]|is_unique[residents.document_id]',
            'primary_phone' => 'required|regex_match[/^05[69][0-9]{7}$/]',
            'backup_phone'  => 'permit_empty|regex_match[/^05[69][0-9]{7}$/]',
            'dob'           => 'required|valid_date[Y-m-d]',
            'marital_status'=> 'required',
        ];

        $errors = [
            'document_id' => [
                'regex_match' => 'يجب أن يتكون رقم الهوية من 9 أرقام فقط دون أي أحرف.',
                'is_unique'   => 'رقم الهوية هذا مسجل بالفعل في النظام.'
            ],
            'primary_phone' => [
                'regex_match' => 'يجب أن يكون رقم الهاتف مكوناً من 10 أرقام ويبدأ بـ 056 أو 059 بدون مقدمة دولية.'
            ],
            'backup_phone' => [
                'regex_match' => 'يجب أن يكون رقم الهاتف الاحتياطي مكوناً من 10 أرقام ويبدأ بـ 056 أو 059.'
            ]
        ];

        return $this->validate($rules, $errors);
    }

    /**
     * Loops through dynamic arrays once to map structural context counts
     */
    private function tallyFamilyMembers(array $members): array
    {
        $tally = ['children' => 0, 'spouses' => 0];

        foreach ($members as $m) {
            $relationship = $m['relationship_type'] ?? '';
            if ($relationship === 'Child') {
                $tally['children']++;
            } elseif ($relationship === 'Spouse') {
                $tally['spouses']++;
            }
        }

        return $tally;
    }

    /**
     * Submits primary head of household properties payload to the DB layer
     */
    private function savePrimaryResident(int $childrenCount, string $plainAccessCode): int
    {
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
            'is_active'          => 0
        ];

        $this->residentModel->save($residentData);
        return (int) $this->residentModel->getInsertID();
    }

    /**
     * Populates supporting child structural array blocks to sub-table
     */
    private function saveFamilyMembers(int $residentId, array $members): void
    {
        foreach ($members as $member) {
            if (empty($member['full_name'])) {
                continue;
            }

            $this->familyModel->save([
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

    /**
     * Generates a human-friendly plain text secure verification access token
     */
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