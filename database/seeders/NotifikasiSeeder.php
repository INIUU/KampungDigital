<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $userIds = DB::table('users')->pluck('id');

        if ($userIds->isEmpty()) {
            $this->command->warn('Tidak ada pengguna, seeder Notifikasi dilewati.');
            return;
        }

        $types = ['info', 'warning', 'success', 'error'];
        $categories = ['kas', 'sistem', 'pengumuman', 'reminder'];

        foreach (range(1, 20) as $i) {
            $read = rand(0, 1) === 1;
            DB::table('notifikasis')->insert([
                'user_id' => $userIds->random(),
                'judul' => 'Pemberitahuan ' . $i,
                'pesan' => $faker->sentence(12),
                'tipe' => $types[array_rand($types)],
                'kategori' => $categories[array_rand($categories)],
                'data' => json_encode(['reference' => 'notif-' . $i]),
                'dibaca' => $read,
                'dibaca_pada' => $read ? now()->subDays(rand(0, 5)) : null,
                'created_at' => now()->subDays(rand(0, 10)),
                'updated_at' => now(),
            ]);
        }
    }
}
