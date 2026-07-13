<?php

namespace App\Controllers;

use App\Controllers\BaseController;
// use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ActivityModel;
use App\Models\ResidentModel;
use App\Models\DonationModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class DashboardController extends BaseController
{
    protected $activityModel;
    protected $residentModel;
    protected $donationModel;


    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Bootstrap all three operational models globally
        $this->activityModel = new ActivityModel();
        $this->residentModel = new ResidentModel();
        $this->donationModel = new DonationModel();
    }

   public function index()
    {
        $db = \Config\Database::connect();

        // 1. Calculate Complete Active Headcount (Adults + Their Children)
        $activeAdultsCount = $this->residentModel->where('is_active', 1)->countAllResults();
        $childrenSumRow    = $this->residentModel->selectSum('children_count')->where('is_active', 1)->first();
        $totalChildrenCount = $childrenSumRow['children_count'] ?? 0;
        $totalHeadcount    = $activeAdultsCount + $totalChildrenCount;

        // 2. Financial Ledger Sums
        $totalDonationsRow = $this->donationModel->selectSum('amount')->first();
        $totalDonations    = $totalDonationsRow['amount'] ?? 0.00;

        $totalExpensesRow  = $this->activityModel->selectSum('cost')->first();
        $totalExpenses     = $totalExpensesRow['cost'] ?? 0.00;
        $netBalance        = $totalDonations - $totalExpenses;

        // 3. NEW MATERIAL AID METRICS COUNTERS
        $waterDeliveries    = $this->activityModel->where('aid_category', 'Water Supply')->countAllResults();
        $foodDistributed    = $this->activityModel->where('aid_category', 'Food Basket')->countAllResults();
        $hygieneDistributed = $this->activityModel->where('aid_category', 'Hygiene Kit')->countAllResults();

        // 4. Feeds for the bottom layout rows
        $recentActivities = $this->activityModel->orderBy('created_at', 'DESC')->findAll(5);
        $recentResidents  = $this->residentModel->orderBy('created_at', 'DESC')->findAll(5);

        $data = [
            'page_title'          => 'Camp Management Dashboard',
            'total_headcount'     => $totalHeadcount,
            'active_adults'       => $activeAdultsCount,
            'active_children'     => $totalChildrenCount,
            'net_balance'         => $netBalance,

            // Pass these material variables to the view array list
            'water_deliveries'    => $waterDeliveries,
            'food_distributed'    => $foodDistributed,
            'hygiene_distributed' => $hygieneDistributed,

            'recent_activities'   => $recentActivities,
            'recent_residents'    => $recentResidents
        ];

        return view('dashboard/index', $data);
    }
}
