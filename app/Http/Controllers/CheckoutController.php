<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to proceed with checkout.');
        }

        $cart = session()->get('cart', []);
        $total = $this->calculateTotal($cart);

        if (empty($cart)) {
            return redirect()->route('beats.index')->with('error', 'Your cart is empty!');
        }

        return view('checkout.index', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cod,esewa,card'
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->with('beat')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('beats.index')->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();

        try {
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->beat->price * $item->quantity;
            }

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'order_status' => 'confirmed',
                'payment_status' => 'paid',
                'payment_method' => $request->payment_method
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'beat_id' => $item->beat_id,
                    'quantity' => $item->quantity,
                    'price' => $item->beat->price
                ]);
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('order.confirmation', $order)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong! Please try again.');
        }
    }

    private function calculateTotal(array $cart): float
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
