<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-role {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test role checking logic';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = \App\Models\User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return Command::FAILURE;
        }
        
        $this->info("User: {$user->email}");
        $this->info("Role raw: " . json_encode($user->role));
        $this->info("Role type: " . gettype($user->role));
        
        if (is_array($user->role)) {
            $this->info("Role is array: " . implode(',', $user->role));
        } else {
            $this->info("Role is string: {$user->role}");
        }
        
        // Test the checking logic
        $roleArray = (array) $user->role;
        $this->info("Role as array: " . json_encode($roleArray));
        $this->info("in_array('STAFF', roleArray): " . (in_array('STAFF', $roleArray) ? 'true' : 'false'));
        
        return Command::SUCCESS;
    }
}
