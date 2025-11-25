<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Analytics extends Controller
{
  public function index()
  {
    // Fetch all users (optional, for table or detailed view)
    $users = User::all();

    // Total counts
    $totalUsers = User::where('role', 'user')->count();
    $totalProviders = User::where('role', 'provider')->count();
    $totalAdmins = User::where('role', 'admin')->count();
    $activeUsers = User::where('is_verified', 1)->count();

    // Percent active users
    $activePercent = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100) : 0;

    // Users added this month
    $usersThisMonth = User::where('role', 'user')
      ->whereMonth('created_at', now()->month)
      ->count();
    $providersThisMonth = User::where('role', 'provider')
      ->whereMonth('created_at', now()->month)
      ->count();

    // Monthly user growth (example for chart)
    $monthlyUsers = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
      ->where('role', 'user')
      ->groupBy('month')
      ->orderBy('month')
      ->pluck('count', 'month')
      ->toArray();

    // Ensure 12 months in the array
    $monthlyUsersFull = [];
    for ($i = 1; $i <= 12; $i++) {
      $monthlyUsersFull[] = $monthlyUsers[$i] ?? 0;
    }
    $monthlyUsers = $monthlyUsersFull;

    return view('content.dashboard.dashboards-analytics', compact(
      'users',
      'totalUsers',
      'totalProviders',
      'totalAdmins',
      'activeUsers',
      'activePercent',
      'usersThisMonth',
      'providersThisMonth',
      'monthlyUsers'
    ));
  }
}
