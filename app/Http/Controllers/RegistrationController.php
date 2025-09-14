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

    public function storeMerchant(Request $request)
    {
        return $this->store($request, 'MERCHANT');
    }

    public function storeStaff(Request $request)
    {
        return $this->store($request, 'STAFF');
    }

    private function store(Request $request, string $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:90'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'digits_between:11,13', 'unique:users,phone'],
        ]);

        $baseUsername = Str::slug($validated['name']);
        $username = $baseUsername;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $i++;
            $username = $baseUsername . '-' . $i;
        }

        $phone = $validated['phone'] ?? ('08' . str_pad((string) random_int(0, 99999999999), 11, '0', STR_PAD_LEFT));
        while (User::where('phone', $phone)->exists()) {
            $phone = '08' . str_pad((string) random_int(0, 99999999999), 11, '0', STR_PAD_LEFT);
        }

        User::create([
            'name' => $validated['name'],
            'username' => $username,
            'email' => $validated['email'],
            'phone' => $phone,
            'role' => $role,
            'status' => 'INACTIVE',
            'password' => Hash::make($validated['password']),
        ]);

        $message = $role === 'STAFF'
            ? 'Registration submitted. Admin will approve your staff account.'
            : 'Registration submitted. Admin or staff will approve your merchant account.';

        return redirect()->route('filament.auth.login')->with('status', $message);
    }
}


