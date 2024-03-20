<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = config('auth.providers.users.model');

        $user = tap((new $model)->forceFill([
            'email' => 'tabiblib_root@tabiblib.com',
            'first_name' => 'Root',
            'last_name' => 'User',
            'password' => Hash::make('123456789abcd'),
            'email_verified_at' => now()->toDateTimeString(),
            'last_login_at' => now()->toDateTimeString(),
            'last_login_ip' => request()->getClientIp(),
        ]))->save();

        $user->assignRole('root');

        $user = tap((new $model)->forceFill([
            'email' => 'tabiblib_admin@tabiblib.com',
            'first_name' => 'Admin',
            'last_name' => 'Manager',
            'password' => Hash::make('123456789abcd'),
            'email_verified_at' => now()->toDateTimeString(),
            'last_login_at' => now()->toDateTimeString(),
            'last_login_ip' => request()->getClientIp(),
        ]))->save();

        $user->assignRole('admin');
    }
}
