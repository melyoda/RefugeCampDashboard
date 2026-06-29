<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ActivityModel;
// use CodeIgniter\HTTP\ResponseInterface;

class ActivitiesController extends BaseController
{
    // List all logged activities
    public function index()
    {
        $model = new ActivityModel();
        //use database query to fetch all activities and order by created_at descending
        $data = [
            'page_title' => 'Activities Log',
            'activities' => $model->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('activities/index', $data);
    }

    // Show the "Log New Activity" Form
    public function create()
    {
        return view('activities/create', ['page_title' => 'Log New Activity']);
    }

    // Process the form submission & upload file
    public function store()
    {
        $model = new ActivityModel();

        // Simple validation rule matching the fields
        $rules = [
            'title' => 'required|min_length[3]',
            'cost'  => 'required|decimal',
            'receipt' => 'permit_empty|uploaded[receipt]|max_size[receipt,4096]|ext_in[receipt,png,jpg,jpeg,pdf]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $receiptPath = null;
        $file = $this->request->getFile('receipt');

        // Handle file upload if a user attaches a receipt image/PDF
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/receipts', $newName);

            // Save a public-accessible URL path in the database
            $receiptPath = 'uploads/receipts/' . $newName;
        }

        // Save into XAMPP Database
        $model->save([
            'title'        => $this->request->getPost('title'),
            'description'  => $this->request->getPost('description'),
            'cost'         => $this->request->getPost('cost'),
            'receipt_path' => $receiptPath,
        ]);

        return redirect()->to('activities')->with('success', 'Activity logged successfully!');
    }

    public function edit($id)
    {
        $model = new ActivityModel();
        $activity = $model->find($id);

        if (!$activity) {
            return redirect()->to('activities')->with('error', 'Activity not found.');
        }

        return view('activities/edit', [
            'page_title' => 'Edit Activity',
            'activity'   => $activity
        ]);
    }

    // Process the update submission
    public function update($id)
    {
        $model = new ActivityModel();
        $activity = $model->find($id);

        if (!$activity) {
            return redirect()->to('activities')->with('error', 'Activity not found.');
        }

        $rules = [
            'title' => 'required|min_length[3]',
            'cost'  => 'required|decimal',
            'receipt' => 'permit_empty|uploaded[receipt]|max_size[receipt,4096]|ext_in[receipt,png,jpg,jpeg,pdf]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $receiptPath = $activity['receipt_path']; // Keep the old path by default
        $file = $this->request->getFile('receipt');

        // Check if a new file was uploaded to replace the old one
        if ($file && $file->isValid() && !$file->hasMoved()) {
        // Optional: Delete the old file from FCPATH . $activity['receipt_path'] if it exists
        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/receipts', $newName);
        $receiptPath = 'uploads/receipts/' . $newName;
    }

        $model->update($id, [
            'title'        => $this->request->getPost('title'),
            'description'  => $this->request->getPost('description'),
            'cost'         => $this->request->getPost('cost'),
            'receipt_path' => $receiptPath,
        ]);

        return redirect()->to('activities')->with('success', 'Activity updated successfully!');
    }

    // Delete an activity record
    public function delete($id)
    {
        $model = new ActivityModel();
        if ($model->find($id)) {
            $model->delete($id);
            return redirect()->to('activities')->with('success', 'Activity deleted successfully.');
        }
        return redirect()->to('activities')->with('error', 'Activity not found.');
    }
}