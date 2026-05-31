<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        $order->load('user', 'items.beat');
        return view('admin.orders.show', compact('order'));
    }
    
    public function updateStatus(Order $order, $status)
    {
        if (in_array($status, ['confirmed', 'cancelled'])) {
            $order->update(['order_status' => $status]);
        }
        
        return redirect()->back()->with('success', 'Order status updated!');
    }
}