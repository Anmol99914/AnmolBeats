<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to proceed with checkout.');
        }

        $cartItems = Cart::where('user_id', Auth::id())->with('beat')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('beats.index')->with('error', 'Your cart is empty!');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->beat->price * $item->quantity;
        }

        return view('checkout.index', compact('cartItems', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cod,stripe'
        ]);
    
        // COD orders go through here, Stripe is handled by stripeProcess
        if ($request->payment_method === 'stripe') {
            return redirect()->route('checkout.stripe');
        }
    
        $cartItems = Cart::where('user_id', Auth::id())->with('beat')->get();
    
        if ($cartItems->isEmpty()) {
            return redirect()->route('beats.index')->with('error', 'Your cart is empty!');
        }
    
        DB::beginTransaction();
    
        try {
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->beat->price;
            }
    
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'cod'
            ]);
    
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'beat_id' => $item->beat_id,
                    'quantity' => 1,
                    'price' => $item->beat->price
                ]);
            }
    
            Cart::where('user_id', Auth::id())->delete();
    
            DB::commit();
    
            return redirect()->route('orders.history')->with('success', 'Order placed! Payment is pending until delivery confirmation.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function stripe()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('beat')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('beats.index')->with('error', 'Your cart is empty!');
        }
        
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->beat->price;
        }
        
        return view('checkout.stripe', compact('total'));
    }

    public function stripeProcess(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required',
            'cardholder_name' => 'required'
        ]);
        
        $cartItems = Cart::where('user_id', Auth::id())->with('beat')->get();
        
        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'error' => 'Your cart is empty!']);
        }
        
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->beat->price * $item->quantity;
        }
        
        Stripe::setApiKey(config('services.stripe.secret'));
        
        try {
            $intent = PaymentIntent::create([
                'amount' => $total * 100,
                'currency' => 'usd',
                'payment_method' => $request->payment_method_id,
                'confirm' => true,
                'payment_method_types' => ['card'],
            ]);
            
            if ($intent->status === 'succeeded') {
                DB::beginTransaction();
                
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_amount' => $total,
                    'order_status' => 'confirmed',
                    'payment_status' => 'paid',
                    'payment_method' => 'stripe',
                    'stripe_charge_id' => $intent->latest_charge, // Save the charge ID
                ]);
                session()->flash('just_placed', true);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'beat_id' => $item->beat_id,
                        'quantity' => 1,
                        'price' => $item->beat->price
                    ]);
                }
                
                Cart::where('user_id', Auth::id())->delete();
                
                DB::commit();
                
                return response()->json([
                    'success' => true, 
                    'redirect' => route('order.confirmation', $order)
                ]);
            } else {
                return response()->json(['success' => false, 'error' => 'Payment failed.']);
            }
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}