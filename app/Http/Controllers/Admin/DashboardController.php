<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BusinessConfiguration;
use App\Models\BusinessScenario;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalBusinesses = BusinessConfiguration::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalBusinesses'
        ));
    }
}
