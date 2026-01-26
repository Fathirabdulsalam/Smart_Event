<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Admin Smart Event',
                'email' => 'admin@example.com',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'created_at' => now(),
            ],
            [
                'name' => 'User Smart Event',
                'email' => 'user@example.com',
                'password' => bcrypt('password123'),
                'role' => 'user',
                'created_at' => now(),
            ]
        ]);
    }
}
