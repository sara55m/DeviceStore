<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Enums\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');
        throw_unless($email && $password, \RuntimeException::class, 'Set ADMIN_EMAIL and ADMIN_PASSWORD in .env');

        $admin = User::firstOrNew(['email' => $email]);
        $admin->name = 'Admin';
        $admin->password = Hash::make($password);
        $admin->role = Role::ADMIN;
        $admin->email_verified_at = now();
        $admin->save();
    }
}
