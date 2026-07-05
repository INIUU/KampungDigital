<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class RwSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $desaIds = DB::table('desas')->pluck('id')->toArray();
        $data = [];
        foreach ($desaIds as $desaId) {
            for ($i = 1; $i <= 3; $i++) {
                $data[] = [
                    'desa_id' => $desaId,
                    'nama_rw' => 'RW 00' . $i,
                    'no_rw' => $i,
                    'alamat' => $faker->streetAddress,
                    'no_telpon' => $faker->optional()->phoneNumber,
                    'saldo' => $faker->randomFloat(2, 100000, 10000000),
                    'status' => 'aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('rws')->insert($data);
    }
}
