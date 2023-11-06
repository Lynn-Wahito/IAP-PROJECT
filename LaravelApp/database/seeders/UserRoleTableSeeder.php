<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch the user and roles
    $user = User::where('email', 'test@example.com')->first();
    $hostRole = Role::where('role_name', 'host')->first();
    $customerRole = Role::where('role_name', 'customer')->first();

    // Assign the roles to the user
    $user->roles()->attach($hostRole->id);
    $user->roles()->attach($customerRole->id);
    }
}
