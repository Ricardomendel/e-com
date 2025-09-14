<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-admin {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete existing ADMIN/STAFF users and create a fresh ADMIN with the provided credentials';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $password = (string) $this->argument('password');

        // Delete existing admin/staff users
        $deleted = User::query()
            ->where('role', 'like', '%ADMIN%')
            ->orWhere('role', 'like', '%STAFF%')
            ->delete();

        $this->info("Deleted {$deleted} admin/staff users.");

        // Prepare unique username and phone
        $baseUsername = 'admin';
        $username = $baseUsername;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $i++;
            $username = $baseUsername . '-' . $i;
        }

        $phone = '08' . str_pad((string) random_int(0, 99999999999), 11, '0', STR_PAD_LEFT);
        while (User::where('phone', $phone)->exists()) {
            $phone = '08' . str_pad((string) random_int(0, 99999999999), 11, '0', STR_PAD_LEFT);
        }

        // Create the new admin
        $user = User::create([
            'name' => 'Admin',
            'username' => $username,
            'email' => $email,
            'phone' => $phone,
            'role' => 'ADMIN',
            'status' => 'ACTIVE',
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $this->info("Created admin: {$user->email} / (password hidden)");
        return Command::SUCCESS;
    }
}


