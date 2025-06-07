<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:test-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user for development';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $this->info("Usuario de prueba creado:");
        $this->info("Email: test@test.com");
        $this->info("Password: password");
        $this->info("Username: testuser");
        
        return 0;
    }
}
