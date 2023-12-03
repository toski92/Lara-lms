<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'username' => 'admin',
                'password' => Hash::make('111'),
                'role' => 'admin',
                'status' => '1'
            ],
            // Instructor
            [
                'name' => 'Instructor',
                'email' => 'instructor@gmail.com',
                'username' => 'instructor',
                'password' => Hash::make('111'),
                'role' => 'instructor',
                'status' => '1'
            ],
            // User
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'username' => 'user',
                'password' => Hash::make('111'),
                'role' => 'user',
                'status' => '1'
            ]
        ],
    );
    }
}
