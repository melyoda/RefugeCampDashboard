<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ResidentModel;
// use CodeIgniter\HTTP\ResponseInterface;
// use CodeIgniter\Controller;

class ResidentsController extends BaseController
{
    /**
     * Lists all current and past residents (excluding hard DB purges)
     */
    public function index()
    {
        $model = new ResidentModel();

        $data = [
            'title'     => 'Residents Database',
            'residents' => $model->orderBy('is_active', 'DESC')->orderBy('last_name', 'ASC')->findAll()
        ];

        $data['active_residents']  = $model->where('is_active', 1)->findAll();
        $data['pending_residents'] = $model->where('is_active', 0)->findAll();

        return view('residents/index', $data);
    }

    /**
     * Renders enrollment form
     */
    public function create()
    {
        return view('residents/create', ['title' => 'Enroll New Resident']);
    }

    /**
     * Stores enrollment payload
     */
    // public function store()
    // {
    //     $rules = [
    //         'first_name' => 'required|min_length[2]|max_length[100]',
    //         'last_name'  => 'required|min_length[2]|max_length[100]',

    //         'document_id'    => 'required|min_length[3]|max_length[100]|is_unique[residents.document_id,id,{id}]',
    //         'primary_phone'  => 'required|min_length[7]|max_length[20]',
    //     ];

    //     if (!$this->validate($rules)) {
    //         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    //     }

    //     $model = new ResidentModel();
    //     $model->save([
    //         'document_id' => $this->request->getPost('document_id') ?: null,
    //         'first_name'     => $this->request->getPost('first_name'),
    //         'last_name'      => $this->request->getPost('last_name'),
    //         'full_name'      => $this->request->getPost('full_name'),
    //         'primary_phone'  => $this->request->getPost('primary_phone'),
    //         'backup_phone'   => $this->request->getPost('backup_phone'),
    //         'marital_status' => $this->request->getPost('marital_status'),
    //         'children_count' => $this->request->getPost('children_count') !== '' ? $this->request->getPost('children_count') : 0,
    //         'notes'          => $this->request->getPost('notes'),
    //         'is_active'      => 1
    //     ]);

    //     return redirect()->to('residents')->with('success', 'Resident enrolled successfully.');
    // }
    public function store()
    {
        $rules = [
            'first_name'    => 'required|min_length[2]|max_length[100]',
            'last_name'     => 'required|min_length[2]|max_length[100]',
            // Cleaned up the validation for a brand-new insert
            'document_id'   => 'required|min_length[3]|max_length[100]|is_unique[residents.document_id]',
            'primary_phone' => 'required|min_length[7]|max_length[20]',
            // Added the new rules
            'dob'            => 'required|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 1. Generate the human-readable code right here
        $plainAccessCode = $this->generateSecureAccessCode();

        $model = new ResidentModel();
        $model->save([
            'document_id'        => $this->request->getPost('document_id') ?: null,
            // 2. Save the cryptographically hashed version safely to the DB
            'access_code_hash'   => password_hash($plainAccessCode, PASSWORD_BCRYPT),
            'status'             => 0,
            'first_name'         => $this->request->getPost('first_name'),
            'last_name'          => $this->request->getPost('last_name'),
            'full_name'          => trim($this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name')),
            'primary_phone'      => $this->request->getPost('primary_phone'),
            'backup_phone'       => $this->request->getPost('backup_phone'),
            'marital_status'     => $this->request->getPost('marital_status'),
            'children_count'     => 0, // Handled automatically by our sub-table now!
            'notes'              => $this->request->getPost('notes'),
            // 3. Capture our brand new inputs from the form
            'dob'                => $this->request->getPost('dob'),
            'has_disability'     => $this->request->getPost('has_disability') ?? 0,
            'disability_details' => $this->request->getPost('disability_details') ?: null,
            'is_active'          => 1
        ]);

        // 4. Redirect, but pass the plain code so you can show it on screen exactly ONCE
        return redirect()->to('residents')->with('success_modal', [
            'name' => trim($this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name')),
            'id'   => $this->request->getPost('document_id'),
            'code' => $plainAccessCode
        ]);
    }

    /**
     * Renders editing interface
     */
    public function edit($id = null)
    {
        $model = new ResidentModel();
        $resident = $model->find($id);

        if (!$resident) {
            return redirect()->to('residents')->with('error', 'Resident profile not found.');
        }

        // return view('residents/edit', [
        //     'title'    => 'Edit Resident Profile',
        //     'resident' => $resident
        // ]);

        $db = \Config\Database::connect();
        $history = $db->table('activity_residents')
                    ->join('activities', 'activities.id = activity_residents.activity_id')
                    ->where('activity_residents.resident_id', $id)
                    ->where('activities.deleted_at', null) // Filter out soft-deleted activities
                    ->orderBy('activities.created_at', 'DESC')
                    ->select('activities.title, activities.cost, activities.created_at, activities.id')
                    ->get()
                    ->getResultArray();

        return view('residents/edit', [
            'title'    => 'Edit Resident Profile',
            'resident' => $resident,
            'history'  => $history
        ]);
    }

    /**
     * Processes profile updates and changes to dynamic flags
     */
    public function update($id = null)
    {
        $model = new ResidentModel();
        if (!$model->find($id)) {
            return redirect()->to('residents')->with('error', 'Resident profile not found.');
        }

        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name'  => 'required|min_length[2]|max_length[100]',

            'document_id'    => 'required|min_length[3]|max_length[100]|is_unique[residents.document_id,id,{id}]',
            'primary_phone'  => 'required|min_length[7]|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->update($id, [
            'document_id'    => $this->request->getPost('document_id') ?: null,
            'first_name'     => $this->request->getPost('first_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'full_name'      => $this->request->getPost('full_name'),
            'primary_phone'  => $this->request->getPost('primary_phone'),
            'backup_phone'   => $this->request->getPost('backup_phone'),
            'marital_status' => $this->request->getPost('marital_status'),
            'children_count' => $this->request->getPost('children_count') !== '' ? $this->request->getPost('children_count') : 0,
            'notes'          => $this->request->getPost('notes'),
            'is_active'      => $this->request->getPost('is_active') ?? 0
        ]);

        return redirect()->to('residents')->with('success', 'Profile updated successfully.');
    }

    /**
     * Safe Soft Deletion
     */
    public function delete($id = null)
    {
        $model = new ResidentModel();
        if ($model->find($id)) {
            $model->delete($id);
            return redirect()->to('residents')->with('success', 'Resident record safely archived.');
        }

        return redirect()->to('residents')->with('error', 'Record not found.');
    }


        private function generateSecureAccessCode(): string
    {
        // Generates a simple, distinct 6-character alphanumeric string (excluding confusing characters like O or 0)
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $chars[rand(0, strlen($chars) - 1)];
        }

        // Formats it cleanly as XXX-XXX for readability on printed slips
        return substr($code, 0, 3) . '-' . substr($code, 3, 3);
    }
}