<?php

namespace App\Http\Controllers;

use App\Models\Beat;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view your cart.');
        }
        
        $cartItems = Cart::where('user_id', Auth::id())->with('beat')->get();
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->beat->price;
        }
        
        return view('cart.index', compact('cartItems', 'total'));
    }
    
    public function add(Request $request, Beat $beat)
    {
        if (!Auth::check()) {
            session()->put('pending_cart_item', $beat->id);
            return redirect()->route('login')->with('error', 'Please login to add items to cart.');
        }
        
        // Check if item already in cart - digital products only need 1 quantity
        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('beat_id', $beat->id)
                        ->first();
        
        if ($cartItem) {
            return redirect()->back()->with('error', 'This beat is already in your cart!');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'beat_id' => $beat->id,
                'quantity' => 1
            ]);
        }
        
        return redirect()->back()->with('success', 'Beat added to cart!');
    }
    
    public function remove(Request $request, int $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        Cart::where('user_id', Auth::id())
            ->where('beat_id', $id)
            ->delete();
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }
    
    public function update(Request $request, int $id)
    {
        // For digital products, we don't need quantity updates
        // This method is kept for compatibility but doesn't do anything
        return response()->json(['success' => true]);
    }
}