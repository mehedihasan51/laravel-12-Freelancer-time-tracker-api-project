<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('users')->insert([
            [
                'id'                   => 1,
                'name'                 => 'admin',
                'email'                => 'admin@admin.com',
                'email_verified_at'    => Carbon::now(),
                'password'             => Hash::make('12345678'),
                'role'                 => 'admin',
                'status'               => 'active',
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'id'                   => 2,
                'name'                 => 'freelancer',
                'email'                => 'freelancer@freelancer.com',
                'email_verified_at'    => Carbon::now(),
                'password'             => Hash::make('12345678'),
                'role'                 => 'freelancer',
                'status'               => 'active',
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
        ]);
    }
}
