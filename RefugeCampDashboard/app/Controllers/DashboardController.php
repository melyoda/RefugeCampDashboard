<?php

namespace App\Controllers;

use App\Controllers\BaseController;
// use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
    {
       // This is the data we pass directly into the view layout
        $data = [
            'page_title' => 'Main Camp Overview'
        ];

        return view('dashboard_home', $data);
    }
}
