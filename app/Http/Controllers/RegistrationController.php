<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function showMerchant()
    {
        return view('auth.register_merchant');
    }

    public function showStaff()
    {
        return view('auth.register_staff');
    }

    public function showCustomer()
    {
        return view('auth.register_customer');
    }

    public function storeMerchant(Request $request)
    {
        return $this->store($request, 'MERCHANT');
    }

    public function storeStaff(Request $request)
    {
        return $this->store($request, 'STAFF');
    }

    public function storeCustomer(Request $request)
    {
        return $this->store($request, 'CUSTOMER');
    }

    private function store(Request $request, string $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:90'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'digits:10', 'unique:users,phone'],
        ]);

        $baseUsername = Str::slug($validated['name']);
        $username = $baseUsername;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $i++;
            $username = $baseUsername . '-' . $i;
        }

        $phone = $validated['phone'] ?? ('0' . str_pad((string) random_int(200000000, 999999999), 9, '0', STR_PAD_LEFT));
        while (User::where('phone', $phone)->exists()) {
            $phone = '0' . str_pad((string) random_int(200000000, 999999999), 9, '0', STR_PAD_LEFT);
        }

        try {
            User::create([
            'name' => $validated['name'],
            'username' => $username,
            'email' => $validated['email'],
            'phone' => $phone,
            'role' => $role,
            'status' => $role === 'CUSTOMER' ? 'ACTIVE' : 'INACTIVE', // Customers are auto-approved
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
            'city_id' => null,
            'nik' => null,
            'address' => null,
        ]);
        } catch (\Throwable $e) {
            \Log::error('Registration failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['name' => 'Registration failed. Please try again.'])->withInput();
        }

        // Different messages and redirects based on role
        if ($role === 'CUSTOMER') {
            // Customers are auto-approved, redirect to login
            return redirect('/')->with('success', 'Registration successful! You can now login to start shopping.');
        }

        $message = $role === 'STAFF'
            ? 'Registration submitted. An administrator will review and approve your staff account.'
            : 'Registration submitted. An administrator or staff member will review and approve your merchant account.';

        return redirect()->route('auth.pending')->with('pending_message', $message);
    }
}


