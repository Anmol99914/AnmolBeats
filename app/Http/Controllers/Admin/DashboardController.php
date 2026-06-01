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
        
        // Calculate revenue: Only paid orders, exclude refunded
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        
        // Get refunded amount for display
        $refundedAmount = Order::where('payment_status', 'refunded')->sum('total_amount');
        
        // Net revenue = paid - refunded
        $netRevenue = $totalRevenue;
        
        return view('admin.dashboard', compact('totalUsers', 'totalBeats', 'totalOrders', 'totalRevenue', 'refundedAmount', 'netRevenue'));
    }
}