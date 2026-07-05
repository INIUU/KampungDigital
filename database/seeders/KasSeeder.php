<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kas;
use App\Models\Penduduk;
use App\Models\Rt;
use App\Models\PengaturanKas;
use Carbon\Carbon;

class KasSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get global pengaturan kas
        $pengaturan = PengaturanKas::first();
        if (!$pengaturan) {
            $this->command->warn('Tidak ada pengaturan kas, seeder Kas dilewati.');
            return;
        }

        // Get all active penduduk with RT data
        $penduduks = Penduduk::whereHas('kk.rt', function($query) {
            $query->where('status', 'aktif');
        })->where('status', 'aktif')->get();

        $currentYear = now()->year;
        $currentWeek = now()->weekOfYear;

        foreach ($penduduks as $penduduk) {
            $rt = $penduduk->kk->rt;
            if (!$rt) {
                continue;
            }

            for ($week = max(1, $currentWeek - 3); $week <= $currentWeek; $week++) {
                $dueDate = Carbon::now()->setISODate($currentYear, $week, 7)->startOfDay();

                $status = 'belum_bayar';
                $tanggalBayar = null;
                $metodeBayar = null;
                $denda = 0;
                $jumlahDibayar = 0;

                if ($week < $currentWeek) {
                    if (rand(1, 100) <= 70) {
                        $status = 'lunas';
                        $tanggalBayar = $dueDate->copy()->addDays(rand(0, 6))->setTime(rand(8, 20), rand(0, 59), 0);
                        $metodeBayar = collect(['tunai', 'bank_transfer', 'e_wallet', 'qr_code'])->random();
                        $jumlahDibayar = $pengaturan->jumlah_kas_mingguan;
                    } else {
                        $status = 'terlambat';
                        $denda = round($pengaturan->jumlah_kas_mingguan * ($pengaturan->persentase_denda / 100), 2);
                    }
                }

                Kas::create([
                    'penduduk_id' => $penduduk->id,
                    'rt_id' => $rt->id,
                    'minggu_ke' => $week,
                    'tahun' => $currentYear,
                    'jumlah' => $pengaturan->jumlah_kas_mingguan,
                    'denda' => $denda,
                    'tanggal_jatuh_tempo' => $dueDate->format('Y-m-d'),
                    'tanggal_bayar' => $tanggalBayar,
                    'status' => $status,
                    'metode_bayar' => $metodeBayar,
                    'jumlah_dibayar' => $jumlahDibayar,
                ]);
            }
        }
    }
}
