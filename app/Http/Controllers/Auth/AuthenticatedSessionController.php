<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Beat;
use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Merge pending cart items from session into database
        if (session()->has('pending_cart')) {
            $pendingCart = session()->get('pending_cart', []);
            
            foreach ($pendingCart as $item) {
                $beat = Beat::find($item['id']);
                if ($beat) {
                    $cartItem = Cart::where('user_id', Auth::id())
                                    ->where('beat_id', $beat->id)
                                    ->first();
                    
                    if ($cartItem) {
                        $cartItem->quantity += $item['quantity'];
                        $cartItem->save();
                    } else {
                        Cart::create([
                            'user_id' => Auth::id(),
                            'beat_id' => $beat->id,
                            'quantity' => $item['quantity']
                        ]);
                    }
                }
            }
            
            // Clear the pending cart from session
            session()->forget('pending_cart');
        }

        // return redirect()->intended('/cart');
        return redirect()->intended('/');

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}