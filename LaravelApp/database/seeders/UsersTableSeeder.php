<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::table('users')->insert([
                 'name' => 'Test User',
                 'email' => 'test@example.com',
                 'password' => Hash::make('password'),
                 'phone' => '07100100100',
            ]);
       

    

    }
}
