<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class KadesSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'kades@kampungdigital.test';
        if (DB::table('users')->where('email', $email)->exists()) {
            $this->command->info('Kades already exists, skipping.');
            return;
        }

        $userId = DB::table('users')->insertGetId([
            'name' => 'Kepala Desa',
            'email' => $email,
            'role' => 'kades',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'status' => Schema::hasColumn('users', 'status') ? 'active' : null,
        ]);

        $desa = DB::table('desas')->first();
        if ($desa) {
            $penduduk = DB::table('penduduks')
                ->whereExists(function ($query) use ($desa) {
                    $query->select(DB::raw(1))
                        ->from('kks')
                        ->join('rts', 'kks.rt_id', '=', 'rts.id')
                        ->join('rws', 'rts.rw_id', '=', 'rws.id')
                        ->whereColumn('kks.id', 'penduduks.kk_id')
                        ->where('rws.desa_id', $desa->id);
                })
                ->where('status', 'aktif')
                ->inRandomOrder()
                ->first();

            if ($penduduk) {
                DB::table('desas')->where('id', $desa->id)->update(['kepala_desa_id' => $penduduk->id]);
                if (is_null($penduduk->user_id)) {
                    DB::table('penduduks')->where('id', $penduduk->id)->update(['user_id' => $userId]);
                }
            }
        }
    }
}
