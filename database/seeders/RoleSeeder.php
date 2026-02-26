<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'student', 'description' => 'Student user'],
            ['name' => 'teacher', 'description' => 'Teacher user'],
            ['name' => 'counsellor', 'description' => 'Counsellor user'],
            ['name' => 'admin', 'description' => 'Administrator user'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
