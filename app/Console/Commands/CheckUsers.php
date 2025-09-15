<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check users in the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = \App\Models\User::all();
        $this->info("Found {$users->count()} users:");
        
        foreach ($users as $user) {
            $role = is_array($user->role) ? implode(',', $user->role) : $user->role;
            $this->line("{$user->email} - {$role} - {$user->status}");
        }
        
        return Command::SUCCESS;
    }
}
