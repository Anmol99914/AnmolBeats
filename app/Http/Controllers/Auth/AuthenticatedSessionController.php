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

    // Check for pending cart item (singular)
    if (session()->has('pending_cart_item')) {
        $beatId = session()->get('pending_cart_item');
        $beat = Beat::find($beatId);
        
        if ($beat) {
            $cartItem = Cart::where('user_id', Auth::id())
                            ->where('beat_id', $beat->id)
                            ->first();
            
            if ($cartItem) {
                // Already in cart, just keep it (no quantity increment for digital)
                // Do nothing or redirect as needed
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'beat_id' => $beat->id,
                    'quantity' => 1
                ]);
            }
        }
        
        // Clear the pending cart item
        session()->forget('pending_cart_item');
    }

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