<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengaturanKasSeeder extends Seeder
{
    public function run()
    {
        DB::table('pengaturan_kas')->insert([
            'jumlah_kas_mingguan' => 10000,
            'persentase_denda' => 2.0,
            'batas_hari_pembayaran' => 7,
            'hari_peringatan' => 1,
            'auto_generate_weekly' => false,
            'pesan_peringatan' => 'Pengingat: Minggu ini ada pembayaran kas yang harus dilunasi.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
