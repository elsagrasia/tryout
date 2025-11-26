<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
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
        return view('frontend.dashboard.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        
        $request->authenticate();
        
        $request->session()->regenerate();
        
        $notification = array(
            'message' => 'Anda berhasil masuk.',
            'alert-type' => 'success'
        );
        
        // ... (Logika Redirect berdasarkan role) ...
        $url = '';
        if ($request->user()->role === 'admin') {
            $url = 'admin/dashboard';
        } elseif ($request->user()->role === 'instructor') {
            $url = 'instructor/dashboard';
        } elseif ($request->user()->role === 'student') {
            // Asumsi 'index' adalah named route yang benar, misalnya untuk homepage ('/')
            $url = route('index'); 
        }



        return redirect()->intended($url)->with($notification);
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
