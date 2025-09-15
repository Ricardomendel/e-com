<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply this middleware to authenticated users
        if (auth()->check()) {
            $user = auth()->user();
            $role = is_array($user->role) ? $user->role[0] : $user->role;
            
            // If user is trying to access admin routes but is not an admin, redirect them
            if ($request->is('admin/*') && $role !== 'ADMIN') {
                switch ($role) {
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
        }

        return $next($request);
    }
}