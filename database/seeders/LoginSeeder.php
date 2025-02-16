<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('logins')->insert([
            [
             
                'email' => 'pimpinan@example.com',
                'password' => Hash::make('password123'),
                'role' => 'pimpinan',
            ],
            [
                
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ],
            [
                
                'email' => 'kasir@example.com',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
            ],
        ]);
    }
}
