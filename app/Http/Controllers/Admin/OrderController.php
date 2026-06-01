<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Refund;

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
    
    public function updateStatus(Order $order, string $status)
    {
        if (!in_array($status, ['confirmed', 'cancelled'])) {
            return redirect()->back()->with('error', 'Invalid status.');
        }
        
        // For COD orders, when confirming, also mark payment as paid
        if ($status === 'confirmed' && $order->payment_method === 'cod') {
            $order->update([
                'order_status' => 'confirmed',
                'payment_status' => 'paid'
            ]);
            return redirect()->back()->with('success', 'COD order confirmed and marked as paid!');
        }
        
        // For Stripe orders, process refund if cancelling
        if ($status === 'cancelled' && $order->payment_method === 'stripe' && $order->payment_status === 'paid') {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                
                $paymentIntentId = $order->stripe_charge_id;
                
                if ($paymentIntentId) {
                    $refund = Refund::create([
                        'payment_intent' => $paymentIntentId,
                        'amount' => $order->total_amount * 100,
                    ]);
                    
                    $order->update([
                        'order_status' => 'cancelled',
                        'payment_status' => 'refunded'
                    ]);
                    
                    return redirect()->back()->with('success', 'Order cancelled and $' . number_format($order->total_amount, 2) . ' refunded successfully!');
                } else {
                    $order->update([
                        'order_status' => 'cancelled',
                        'payment_status' => 'refunded'
                    ]);
                    
                    return redirect()->back()->with('warning', 'Order cancelled but no Stripe charge ID found. Status updated manually.');
                }
                
            } catch (\Exception $e) {
                $order->update(['order_status' => 'cancelled']);
                return redirect()->back()->with('error', 'Order cancelled but refund failed: ' . $e->getMessage());
            }
        }
        
        // For other orders (eSewa, Card simulated)
        $order->update(['order_status' => $status]);
        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}