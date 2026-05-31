<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function history()
    {
        $orders = auth()->user()->orders()->with('items.beat')->latest()->get();
        return view('orders.history', compact('orders'));
    }
    
    public function confirmation(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        
        $order->load('items.beat');
        return view('orders.confirmation', compact('order'));
    }
}