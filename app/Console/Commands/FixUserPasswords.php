<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FixUserPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-passwords 
                            {--email= : Fix password for specific email}
                            {--password= : New password to set (if not provided, will set default)}
                            {--all : Fix all users with invalid password hashes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix user passwords that are not properly hashed with Bcrypt';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->option('email');
        $newPassword = $this->option('password');
        $fixAll = $this->option('all');

        if ($email) {
            return $this->fixUserPassword($email, $newPassword);
        }

        if ($fixAll) {
            return $this->fixAllPasswords($newPassword);
        }

        $this->error('You must specify --email=<email> or --all flag.');
        $this->info('Usage examples:');
        $this->line('  php artisan users:fix-passwords --email=admin@poachurras.com --password=newpassword');
        $this->line('  php artisan users:fix-passwords --all --password=defaultpassword');

        return Command::FAILURE;
    }

    /**
     * Fix password for a specific user
     */
    protected function fixUserPassword(string $email, ?string $newPassword = null): int
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return Command::FAILURE;
        }

        // Check if password is already properly hashed
        if ($this->isPasswordHashed($user->getAttributes()['password'])) {
            $this->info("Password for user {$email} is already properly hashed.");
            return Command::SUCCESS;
        }

        if (!$newPassword) {
            $newPassword = $this->secret("Enter new password for {$email}:");
            $confirmPassword = $this->secret("Confirm password:");

            if ($newPassword !== $confirmPassword) {
                $this->error("Passwords do not match.");
                return Command::FAILURE;
            }
        }

        // Update password directly in database
        DB::table('users')
            ->where('id', $user->id)
            ->update(['password' => Hash::make($newPassword)]);

        $this->info("✅ Password fixed successfully for {$email}!");

        return Command::SUCCESS;
    }

    /**
     * Fix passwords for all users with invalid hashes
     */
    protected function fixAllPasswords(?string $defaultPassword = null): int
    {
        if (!$defaultPassword) {
            $defaultPassword = $this->secret("Enter default password for users without proper hash:");
            $confirmPassword = $this->secret("Confirm password:");

            if ($defaultPassword !== $confirmPassword) {
                $this->error("Passwords do not match.");
                return Command::FAILURE;
            }
        }

        $users = User::all();
        $fixedCount = 0;

        $this->info("Checking {$users->count()} users...");

        foreach ($users as $user) {
            $rawPassword = $user->getAttributes()['password'];

            if (!$this->isPasswordHashed($rawPassword)) {
                // Update password directly in database
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['password' => Hash::make($defaultPassword)]);
                $this->line("Fixed password for: {$user->email}");
                $fixedCount++;
            }
        }

        if ($fixedCount > 0) {
            $this->info("✅ Fixed passwords for {$fixedCount} user(s).");
        } else {
            $this->info("All passwords are already properly hashed.");
        }

        return Command::SUCCESS;
    }

    /**
     * Check if password is properly hashed with Bcrypt
     */
    protected function isPasswordHashed(string $password): bool
    {
        // Bcrypt hashes start with $2y$ or $2a$ and are 60 characters long
        if (strlen($password) !== 60) {
            return false;
        }

        if (!preg_match('/^\$2[ay]\$\d{2}\$[./A-Za-z0-9]{53}$/', $password)) {
            return false;
        }

        return true;
    }
}

