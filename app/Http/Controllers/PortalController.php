<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $remember = (bool) ($credentials['remember'] ?? false);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            $role = is_array($user->role) ? $user->role[0] : $user->role;
            
            // Check if user is active
            if ($user->status !== 'ACTIVE') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is pending approval or has been deactivated.',
                ])->onlyInput('email');
            }
            
            // Redirect based on user role
            switch ($role) {
                case 'ADMIN':
                    return redirect('/admin');
                case 'STAFF':
                    return redirect()->route('staff.dashboard');
                case 'MERCHANT':
                    return redirect()->route('merchant.dashboard');
                case 'CUSTOMER':
                    return redirect()->route('customer.dashboard');
                default:
                    return redirect('/');
            }
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }
}


