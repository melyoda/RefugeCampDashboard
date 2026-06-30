<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\DonationModel;
use CodeIgniter\HTTP\RequestInterface;
use Psr\Log\LoggerInterface;

class DonationsController extends BaseController
{
    protected $donationModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->donationModel = new DonationModel();
    }

    // List all logged donations
    public function index()
    {
        $data = [
            'page_title' => 'Inbound Donations Ledger',
            'donations'  => $this->donationModel->orderBy('donation_date', 'DESC')->findAll()
        ];

        return view('donations/index', $data);
    }

    // Show Log Donation Form
    public function create()
    {
        return view('donations/create', ['page_title' => 'Log Incoming Funding']);
    }

    // Process Donation Entry
    public function store()
    {
        $rules = [
            'donor_name'    => 'required|min_length[2]|max_length[255]',
            'amount'        => 'required|decimal',
            'donation_date' => 'required|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->donationModel->save([
            'donor_name'    => $this->request->getPost('donor_name'),
            'amount'        => $this->request->getPost('amount'),
            'donation_date' => $this->request->getPost('donation_date'),
            'notes'         => $this->request->getPost('notes'),
        ]);

        return redirect()->to('donations')->with('success', 'Funding transaction logged successfully!');
    }
}