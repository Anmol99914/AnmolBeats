<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function history()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('items.beat')->latest()->get();
        return view('orders.history', compact('orders'));
    }
    
    public function confirmation(Order $order)
    {
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            abort(403);
        }
        
        // If COD and not confirmed, redirect to history
        if ($order->payment_method === 'cod' && $order->payment_status !== 'paid') {
            return redirect()->route('orders.history')->with('info', 'COD orders are confirmed upon delivery.');
        }
        
        // If order is more than 1 hour old and accessed again, redirect to orders history
        if ($order->created_at->diffInMinutes(now()) > 60 && !session('just_placed')) {
            return redirect()->route('orders.history')->with('info', 'Order details can be found in your order history.');
        }
        
        $order->load('items.beat');
        return view('orders.confirmation', compact('order'));
    }
}