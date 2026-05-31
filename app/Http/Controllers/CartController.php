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
            $total += $item->beat->price * $item->quantity;
        }
        
        return view('cart.index', compact('cartItems', 'total'));
    }
    
    public function add(Request $request, Beat $beat)
    {
        // If not logged in, save to session and redirect to login
        if (!Auth::check()) {
            // Store the beat in session
            $pendingCart = session()->get('pending_cart', []);
            $pendingCart[$beat->id] = [
                'id' => $beat->id,
                'title' => $beat->title,
                'price' => $beat->price,
                'quantity' => 1,
                'image' => $beat->image
            ];
            session()->put('pending_cart', $pendingCart);
            
            return redirect()->route('login')->with('error', 'Please login to add items to cart. Your item has been saved!');
        }
        
        // User is logged in - add to database cart
        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('beat_id', $beat->id)
                        ->first();
        
        if ($cartItem) {
            $cartItem->quantity++;
            $cartItem->save();
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
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('beat_id', $id)
                        ->first();
        
        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
        }
        
        return response()->json(['success' => true]);
    }
}