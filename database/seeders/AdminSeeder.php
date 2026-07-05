<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'admin@kampungdigital.test';
        if (DB::table('users')->where('email', $email)->exists()) {
            $this->command->info('Admin already exists, skipping.');
            return;
        }

        $data = [
            'name' => 'Admin Desa',
            'email' => $email,
            'role' => 'admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('users', 'status')) {
            $data['status'] = 'active';
        }

        DB::table('users')->insert($data);
    }
}
