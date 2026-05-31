<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Beat;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalBeats = Beat::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        
        return view('admin.dashboard', compact('totalUsers', 'totalBeats', 'totalOrders', 'totalRevenue'));
    }
}