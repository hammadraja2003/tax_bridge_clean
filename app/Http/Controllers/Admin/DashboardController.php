<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BusinessConfiguration;
use App\Models\BusinessScenario;
use App\Models\BusinessPackage;
class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalBusinesses = BusinessConfiguration::count();
        // Count current active trials
        $totalCurrentTrials = BusinessPackage::where('is_trial', 1)
            ->where('is_active', 1)
            ->count();
        // Count active paid packages (non-trial)
        $totalActivePackages = BusinessPackage::where('is_trial', 0)
            ->where('is_active', 1)
            ->count();
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalBusinesses',
            'totalCurrentTrials',
            'totalActivePackages'
        ));
    }
}
