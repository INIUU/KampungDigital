<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua RT yang ada
        $rtIds = DB::table('rts')->pluck('id');

        if ($rtIds->isEmpty()) {
            $this->command->warn('Tidak ada data RT, seeder KK dilewati.');
            return;
        }

        foreach ($rtIds as $rtId) {
            $kkCount = rand(4, 7);
            for ($i = 0; $i < $kkCount; $i++) {
                DB::table('kks')->insert([
                    'no_kk' => $this->generateNoKk($rtId, $i),
                    'rt_id' => $rtId,
                    'alamat' => 'Jalan RT ' . $rtId . ' No. ' . rand(1, 120),
                    'status' => 'aktif',
                    'tanggal_dibuat' => Carbon::now()->subDays(rand(0, 730)),
                    'keterangan' => rand(0, 1) ? 'Keluarga baru pindahan.' : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function generateNoKk($rtId, $index): string
    {
        $prefix = '327312' . str_pad($rtId, 3, '0', STR_PAD_LEFT);
        return $prefix . str_pad($index + 1, 5, '0', STR_PAD_LEFT);
    }
}
