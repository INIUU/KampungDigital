<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;

class MasyarakatSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $penduduks = DB::table('penduduks')->whereNull('user_id')->inRandomOrder()->take(30)->get();

        if ($penduduks->isEmpty()) {
            $this->command->warn('Tidak ada penduduk tanpa user_id, seeder Masyarakat dilewati.');
            return;
        }

        foreach ($penduduks as $penduduk) {
            $email = "masyarakat{$penduduk->id}@kampungdigital.test";
            if (DB::table('users')->where('email', $email)->exists()) {
                continue;
            }

            $userId = DB::table('users')->insertGetId([
                'name' => $penduduk->nama_lengkap,
                'email' => $email,
                'role' => 'masyarakat',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
                'status' => Schema::hasColumn('users', 'status') ? 'active' : null,
            ]);

            DB::table('penduduks')->where('id', $penduduk->id)->update(['user_id' => $userId]);
        }
    }
}
