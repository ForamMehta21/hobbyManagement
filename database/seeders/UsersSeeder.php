<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);

        // Create the Super Admin user
        $superAdmin = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'hobbymanagement@gmail.com',
            'password' => bcrypt('admin@123'), // Default password
            'status' => 'active',
            'mobile' => '9999999999', // Optional
        ]);

        // Assign the role to the Super Admin user
        $superAdmin->assignRole($superAdminRole);
    }
}
