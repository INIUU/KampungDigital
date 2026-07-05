<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BantuanProposalSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $rwIds = DB::table('rws')->pluck('id');
        $rwUserIds = DB::table('users')->where('role', 'rw')->pluck('id');
        $kadesUserIds = DB::table('users')->where('role', 'kades')->pluck('id');

        if ($rwIds->isEmpty() || $rwUserIds->isEmpty()) {
            $this->command->warn('Tidak ada RW atau pengguna RW, seeder BantuanProposal dilewati.');
            return;
        }

        foreach (range(1, 8) as $i) {
            $submittedBy = $rwUserIds->random();
            $rwId = $rwIds->random();
            $hasReview = rand(0, 100) < 75 && $kadesUserIds->isNotEmpty();
            $status = $hasReview ? $faker->randomElement(['approved', 'rejected']) : 'pending';
            $reviewedBy = $hasReview ? $kadesUserIds->random() : null;
            $approvedAmount = $status === 'approved' ? rand(1000000, 5000000) : null;
            $jumlahBantuan = rand(1000000, 7000000);

            DB::table('bantuan_proposals')->insert([
                'rw_id' => $rwId,
                'submitted_by' => $submittedBy,
                'judul_proposal' => 'Bantuan Sosial RT ' . rand(1, 10),
                'deskripsi' => $faker->paragraph(3),
                'jumlah_bantuan' => $jumlahBantuan,
                'file_proposal' => null,
                'status' => $status,
                'reviewed_by' => $reviewedBy,
                'reviewed_at' => $hasReview ? now()->subDays(rand(1, 14)) : null,
                'catatan_review' => $hasReview ? $faker->sentence : null,
                'jumlah_disetujui' => $approvedAmount,
                'tanggal_pencairan' => $status === 'approved' ? now()->addDays(rand(1, 7)) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
