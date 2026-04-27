<?php

namespace Database\Seeders;


use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */

     //Admin user with email
            // ['email' => 'thehas322@gmail.com '],
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $adminRole = Role::where('name', 'admin')->first();

        $adminUser = User::updateOrCreate(



            ['email' => 'admincollegecare@gmail.com'],
            [
                'name' => 'System Admin',
                'full_name' => 'System Administrator',
                'phone' => '0100000001',
                'password' => 'password',
                'profile_pic' => '/images/default-profile.svg',
            ]
        );

        if ($adminRole) {
            $adminUser->roles()->syncWithoutDetaching([
                $adminRole->id => ['assigned_at' => now()],
            ]);
        }

        User::factory()->create([
            'name' => 'Dummy name',
            'full_name' => 'Dummy name',
            'phone' => '67676676767',
            'email' => ' test@example.com',
            'password' => 'password',
        ]);
    }
}
