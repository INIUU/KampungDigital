<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $users = [
            [
                'name' => 'Admin Desa',
                'email' => 'admin@kampungdigital.test',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => null,
            ],
            [
                'name' => 'Kepala Desa',
                'email' => 'kades@kampungdigital.test',
                'role' => 'kades',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => null,
            ],
        ];

        foreach (range(1, 3) as $i) {
            $users[] = [
                'name' => 'RW ' . $i,
                'email' => "rw{$i}@kampungdigital.test",
                'role' => 'rw',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => null,
            ];
        }

        foreach (range(1, 5) as $i) {
            $users[] = [
                'name' => 'RT ' . $i,
                'email' => "rt{$i}@kampungdigital.test",
                'role' => 'rt',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => null,
            ];
        }

        foreach (range(1, 15) as $i) {
            $users[] = [
                'name' => $faker->name,
                'email' => "user{$i}@kampungdigital.test",
                'role' => 'masyarakat',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => null,
            ];
        }

        foreach ($users as $user) {
            if (config('database.default') === 'sqlite' || Schema::hasColumn('users', 'status')) {
                $user['status'] = 'active';
            }

            DB::table('users')->insert($user);
        }
    }
}
