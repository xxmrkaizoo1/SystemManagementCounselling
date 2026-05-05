<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure roles exist first
        $this->call(RoleSeeder::class);

        // Create or update one admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@collegecare.com'],
            [
                'name' => 'Admin',
                'full_name' => 'System Admin',
                'phone' => '01100000000',
                'email_verified_at' => now(),
                'password' => Hash::make('Admin@12345'),
                'years' => null,
                'programme' => null,
                'profile_pic' => null,
                'remember_token' => Str::random(10),
            ]
        );

        // Attach admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            DB::table('user_role')->updateOrInsert(
                ['user_id' => $admin->id, 'role_id' => $adminRole->id],
                ['assigned_at' => now(), 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // Optional demo booking for "today" counters
        DB::table('booking_requests')->updateOrInsert(
            [
                'user_id' => $admin->id,
                'booking_date' => now()->toDateString(),
                'booking_time' => '10:00 AM',
                'counsellor_name' => 'Demo Counsellor',
                'note' => 'Production seed demo booking',
            ],
            [
                'status' => 'pending',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
