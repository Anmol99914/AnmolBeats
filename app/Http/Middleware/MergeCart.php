<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MergeCart
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && session()->has('cart')) {
            $sessionCart = session()->get('cart', []);
            $userCart = session()->get('user_cart_' . Auth::id(), []);
            
            // Merge carts
            foreach ($sessionCart as $id => $item) {
                if (isset($userCart[$id])) {
                    $userCart[$id]['quantity'] += $item['quantity'];
                } else {
                    $userCart[$id] = $item;
                }
            }
            
            // Save merged cart
            session()->put('user_cart_' . Auth::id(), $userCart);
            session()->put('cart', $userCart);
        }
        
        return $next($request);
    }
}