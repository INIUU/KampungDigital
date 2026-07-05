<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class RtUserSeeder extends Seeder
{
    public function run(): void
    {
        $rts = DB::table('rts')->get();
        if ($rts->isEmpty()) {
            $this->command->warn('Tidak ada RT, seeder RT user dilewati.');
            return;
        }

        foreach ($rts as $rt) {
            $email = "rt{$rt->id}@kampungdigital.test";
            if (DB::table('users')->where('email', $email)->exists()) {
                continue;
            }

            $userId = DB::table('users')->insertGetId([
                'name' => "Ketua RT {$rt->no_rt}",
                'email' => $email,
                'role' => 'rt',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
                'status' => Schema::hasColumn('users', 'status') ? 'active' : null,
            ]);

            $penduduk = DB::table('penduduks')
                ->whereExists(function ($query) use ($rt) {
                    $query->select(DB::raw(1))
                        ->from('kks')
                        ->whereColumn('kks.id', 'penduduks.kk_id')
                        ->where('kks.rt_id', $rt->id);
                })
                ->where('status', 'aktif')
                ->inRandomOrder()
                ->first();

            if ($penduduk) {
                DB::table('penduduks')->where('id', $penduduk->id)->update(['user_id' => $userId]);
                DB::table('rts')->where('id', $rt->id)->update(['ketua_rt_id' => $penduduk->id]);
            }
        }
    }
}
