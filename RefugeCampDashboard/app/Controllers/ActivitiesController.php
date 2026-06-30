<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ActivityModel;
use App\Models\ResidentModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
class ActivitiesController extends BaseController
{
    protected $activityModel;
    protected $residentModel;

    /**
     * Initializes the controller and bootstraps our tracking models
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Always execute the parent framework routine first
        parent::initController($request, $response, $logger);

        // Spin models up once in memory right here
        $this->activityModel = new ActivityModel();
        $this->residentModel = new ResidentModel();
    }

    // List all logged activities
    public function index()
    {
        // $model = new ActivityModel();
        //use database query to fetch all activities and order by created_at descending
        $data = [
            'page_title' => 'Activities Log',
            'activities' => $this->activityModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('activities/index', $data);
    }

    // Show the "Log New Activity" Form
   public function create()
    {
        $data = [
            'page_title' => 'Log New Activity',
            // Pass the active residents to populate checkboxes
            'residents'  => $this->residentModel->where('is_active', 1)->orderBy('last_name', 'ASC')->findAll()
        ];

        return view('activities/create', $data);
    }

    // Process the form submission & upload file
    public function store()
    {
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
        $this->activityModel->save([
            'title'        => $this->request->getPost('title'),
            'description'  => $this->request->getPost('description'),
            'cost'         => $this->request->getPost('cost'),
            'receipt_path' => $receiptPath,
            'is_distributed_aid' => $this->request->getPost('is_distributed_aid') ?? 0,
            'aid_category'       => $this->request->getPost('aid_category') ?: null,
        ]);

        // return redirect()->to('activities')->with('success', 'Activity logged successfully!');
        // --- NEW: Process Junction Table Links for Residents ---
        $activityId = $this->activityModel->getInsertID();
        $selectedResidents = $this->request->getPost('resident_ids');

        if (!empty($selectedResidents) && is_array($selectedResidents)) {
            $db = \Config\Database::connect();
            $linkages = [];
            foreach ($selectedResidents as $resId) {
                $linkages[] = [
                    'activity_id' => $activityId,
                    'resident_id' => $resId,
                    'created_at'  => date('Y-m-d H:i:s')
                ];
            }
            $db->table('activity_residents')->insertBatch($linkages);
        }

        return redirect()->to('activities')->with('success', 'Activity logged successfully!');
    }

    public function edit($id)
    {
        $activity = $this->activityModel->find($id);

        if (!$activity) {
            return redirect()->to('activities')->with('error', 'Activity not found.');
        }

        // --- NEW: Fetch only the linked resident IDs for this specific activity ---
        $db = \Config\Database::connect();
        $linkedResidentRows = $db->table('activity_residents')
                                 ->where('activity_id', $id)
                                 ->select('resident_id')
                                 ->get()
                                 ->getResultArray();

        // Convert the multi-dimensional array into a simple, flat array of IDs: [1, 4, 7]
        $linkedResidentIds = array_column($linkedResidentRows, 'resident_id');

        return view('activities/edit', [
            'page_title'        => 'Edit Activity',
            'activity'          => $activity,
            'residents'         => $this->residentModel->where('is_active', 1)->orderBy('last_name', 'ASC')->findAll(),
            'linkedResidentIds' => $linkedResidentIds // Pass this safe array directly to the template layout
        ]);
    }

    // Process the update submission
    public function update($id)
    {
        // $model = new ActivityModel();
        $activity = $this->activityModel->find($id);

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

        $this->activityModel->update($id, [
            'title'        => $this->request->getPost('title'),
            'description'  => $this->request->getPost('description'),
            'cost'         => $this->request->getPost('cost'),
            'receipt_path' => $receiptPath,
            'is_distributed_aid' => $this->request->getPost('is_distributed_aid') ?? 0,
            'aid_category'       => $this->request->getPost('aid_category') ?: null,
        ]);

        // return redirect()->to('activities')->with('success', 'Activity updated successfully!');
        // --- NEW: Sync Junction Table Links for Residents ---
        $db = \Config\Database::connect();
        $builder = $db->table('activity_residents');

        // Clear old items to prevent duplication strings
        $builder->where('activity_id', $id)->delete();

        $selectedResidents = $this->request->getPost('resident_ids');
        if (!empty($selectedResidents) && is_array($selectedResidents)) {
            $linkages = [];
            foreach ($selectedResidents as $resId) {
                $linkages[] = [
                    'activity_id' => $id,
                    'resident_id' => $resId,
                    'created_at'  => date('Y-m-d H:i:s')
                ];
            }
            $builder->insertBatch($linkages);
        }

        return redirect()->to('activities')->with('success', 'Activity updated successfully!');
    }

    // Delete an activity record
    public function delete($id)
    {
        // $model = new ActivityModel();
       if ($this->activityModel->find($id)) {
            $this->activityModel->delete($id);
            return redirect()->to('activities')->with('success', 'Activity deleted successfully.');
        }
        return redirect()->to('activities')->with('error', 'Activity not found.');
    }

    public function show($id)
    {
        $activity = $this->activityModel->find($id);

        if (!$activity) {
            return redirect()->to('activities')->with('error', 'Activity log not found.');
        }

        $db = \Config\Database::connect();

        // Fetch currently linked resident IDs for this activity
        $linkedResidentRows = $db->table('activity_residents')
                                ->where('activity_id', $id)
                                ->select('resident_id')
                                ->get()
                                ->getResultArray();

        $linkedResidentIds = array_column($linkedResidentRows, 'resident_id');

        return view('activities/show', [
            'page_title'        => 'Activity Distribution Profile',
            'activity'          => $activity,
            'residents'         => $this->residentModel->where('is_active', 1)->orderBy('last_name', 'ASC')->findAll(),
            'linkedResidentIds' => $linkedResidentIds
        ]);
    }

    public function saveDistribution($id)
    {
        $activity = $this->activityModel->find($id);
        if (!$activity) {
            return redirect()->to('activities')->with('error', 'Activity record not found.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('activity_residents');

        // Wipe old recipient links for this specific activity to handle updates safely
        $builder->where('activity_id', $id)->delete();

        $selectedResidents = $this->request->getPost('resident_ids');
        if (!empty($selectedResidents) && is_array($selectedResidents)) {
            $linkages = [];
            foreach ($selectedResidents as $resId) {
                $linkages[] = [
                    'activity_id' => $id,
                    'resident_id' => $resId,
                    'created_at'  => date('Y-m-d H:i:s')
                ];
            }
            $builder->insertBatch($linkages);
        }

        return redirect()->to('activities/show/' . $id)->with('success', 'Distribution logs updated successfully!');
    }
}