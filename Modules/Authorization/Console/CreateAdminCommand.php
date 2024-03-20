<?php

namespace Modules\Authorization\Console;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    protected $name = 'tabiblib:user:admin';

    protected $description = 'Create user with admin role and all permissions.';

    public function handle(): void
    {
        $this->info('Create Admin User with full authorization and permissions.');
        $this->createUser();
        $this->info('Admin created successfully.');
    }

    protected function createUser(): void
    {
        $email = $this->ask('Email Address', 'admin@admin.com');
        $name = $this->ask('Name', 'Super Admin');
        $password = $this->secret('Password');
        $confirmPassword = $this->secret('Confirm Password');

        // Passwords don't match
        if ($password !== $confirmPassword) {
            $this->info('Passwords don\'t match');
        }

        $this->info('Creating admin account...');

        $userData = [
            'email' => $email,
            'first_name' => $name,
            'last_name' => $name,
            'password' => Hash::make($password),
            'email_verified_at' => now()->toDateTimeString(),
            'last_login_at' => now()->toDateTimeString(),
            'last_login_ip' => request()->getClientIp(),
        ];
        $model = config('auth.providers.users.model');

        try {
            $user = tap((new $model)->forceFill($userData))->save();

            $user->assignRole('admin');
        } catch (\Exception | QueryException $e) {
            $this->error($e->getMessage());
        }
    }
}
