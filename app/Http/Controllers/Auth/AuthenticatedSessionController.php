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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]
        ,
        [
            'email.required' => 'Please Enter Your Email',
            'email.email' => 'Please Enter Valid Email',
            'password.required' => 'Please Enter Your Password',
        ]);
        
       $notification = array(
            'message' => 'Login Successfully',
            'alert-type' => 'success'
        );
        
        $url = '';
        if ($request->user()->role === 'admin') {
            $url = 'admin/dashboard';
        } elseif ($request->user()->role === 'instructor') {
            $url = 'instructor/dashboard';
        } elseif ($request->user()->role === 'student') {
            $url = 'dashboard';
        }

        return redirect()->intended($url)->with($notification);
        dd($request->user()->role);
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
