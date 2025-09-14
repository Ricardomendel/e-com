<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminRegistrationController extends Controller
{
    public function show()
    {
        $exists = User::query()
            ->where('role', 'like', '%ADMIN%')
            ->orWhere('role', 'like', '%STAFF%')
            ->exists();

        if ($exists) {
            return to_route('filament.auth.login');
        }

        return view('admin.register');
    }

    public function store(Request $request)
    {
        $exists = User::query()
            ->where('role', 'like', '%ADMIN%')
            ->orWhere('role', 'like', '%STAFF%')
            ->exists();

        if ($exists) {
            return to_route('filament.auth.login');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:90'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $baseUsername = Str::slug($data['name']);
        $username = $baseUsername;
        $suffix = 1;
        while (User::where('username', $username)->exists()) {
            $suffix++;
            $username = $baseUsername.'-'.$suffix;
        }

        $phone = '08'.str_pad((string) random_int(0, 99999999999), 11, '0', STR_PAD_LEFT);
        while (User::where('phone', $phone)->exists()) {
            $phone = '08'.str_pad((string) random_int(0, 99999999999), 11, '0', STR_PAD_LEFT);
        }

        $user = User::create([
            'name' => $data['name'],
            'username' => $username,
            'email' => $data['email'],
            'phone' => $phone,
            'nik' => null,
            'role' => 'ADMIN',
            'balance' => null,
            'status' => 'ACTIVE',
            'address' => null,
            'avatar' => null,
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return redirect('/admin');
    }
}


