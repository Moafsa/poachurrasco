<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create 
                            {--email= : Admin email address}
                            {--name= : Admin name}
                            {--password= : Admin password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a super admin user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->option('email') ?: $this->ask('Enter admin email');
        $name = $this->option('name') ?: $this->ask('Enter admin name');
        $password = $this->option('password') ?: $this->secret('Enter admin password');

        // Validate email format
        $validator = Validator::make(
            ['email' => $email],
            ['email' => 'required|email']
        );

        if ($validator->fails()) {
            $this->error('Invalid email address.');
            return Command::FAILURE;
        }

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->warn("User with email {$email} already exists.");
            
            if (!$this->confirm('Do you want to update this user to admin role?')) {
                $this->info('Operation cancelled.');
                return Command::FAILURE;
            }

            $user = User::where('email', $email)->first();
            $user->role = 'admin';
            $user->is_active = true;
            
            if ($password) {
                $user->password = Hash::make($password);
            }
            
            if ($name && $name !== $user->name) {
                $user->name = $name;
            }
            
            $user->save();
            
            $this->info("✅ User updated to admin successfully!");
            $this->line("Email: {$user->email}");
            $this->line("Name: {$user->name}");
            $this->line("Role: {$user->role}");
            
            return Command::SUCCESS;
        }

        // Create new admin user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->info("✅ Super admin created successfully!");
        $this->line("Email: {$user->email}");
        $this->line("Name: {$user->name}");
        $this->line("Role: {$user->role}");
        $this->newLine();
        $this->line("You can now login at: /login");
        
        return Command::SUCCESS;
    }
}



