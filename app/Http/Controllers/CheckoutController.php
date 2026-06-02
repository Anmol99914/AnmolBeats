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
use Stripe\Checkout\Session;


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
        $cartItems = Cart::where('user_id', Auth::id())->with('beat')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('beats.index')->with('error', 'Your cart is empty!');
        }
        
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->beat->price;
        }
        
        Stripe::setApiKey(config('services.stripe.secret'));
        
        // Create the order FIRST (before Stripe redirect)
        DB::beginTransaction();
        
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'stripe'
            ]);
            
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'beat_id' => $item->beat_id,
                    'quantity' => 1,
                    'price' => $item->beat->price
                ]);
            }
            
            // Don't delete cart yet - only after successful payment
            // Store order ID in session for after payment
            session()->put('pending_order_id', $order->id);
            
            DB::commit();
            
            // Create Stripe checkout session
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Beat Purchase - ' . $cartItems->count() . ' beats',
                        ],
                        'unit_amount' => $total * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('checkout.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.stripe'),
            ]);
            
            return redirect($checkoutSession->url);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.stripe')->with('error', 'Payment setup failed: ' . $e->getMessage());
        }
    }
}