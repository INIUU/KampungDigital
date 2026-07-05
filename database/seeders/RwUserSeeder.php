<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class RwUserSeeder extends Seeder
{
    public function run(): void
    {
        $rws = DB::table('rws')->get();
        if ($rws->isEmpty()) {
            $this->command->warn('Tidak ada RW, seeder RW user dilewati.');
            return;
        }

        foreach ($rws as $rw) {
            $email = "rw{$rw->id}@kampungdigital.test";
            if (DB::table('users')->where('email', $email)->exists()) {
                continue;
            }

            $userId = DB::table('users')->insertGetId([
                'name' => "Ketua RW {$rw->no_rw}",
                'email' => $email,
                'role' => 'rw',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
                'status' => Schema::hasColumn('users', 'status') ? 'active' : null,
            ]);

            $penduduk = DB::table('penduduks')
                ->whereExists(function ($query) use ($rw) {
                    $query->select(DB::raw(1))
                        ->from('kks')
                        ->join('rts', 'kks.rt_id', '=', 'rts.id')
                        ->whereColumn('kks.id', 'penduduks.kk_id')
                        ->where('rts.rw_id', $rw->id);
                })
                ->where('status', 'aktif')
                ->inRandomOrder()
                ->first();

            if ($penduduk) {
                DB::table('penduduks')->where('id', $penduduk->id)->update(['user_id' => $userId]);
                DB::table('rws')->where('id', $rw->id)->update(['ketua_rw_id' => $penduduk->id]);
            }
        }
    }
}
