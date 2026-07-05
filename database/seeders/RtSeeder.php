<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class RtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $rwIds = DB::table('rws')->pluck('id')->toArray();
        $data = [];
        foreach ($rwIds as $rwId) {
            for ($i = 1; $i <= 3; $i++) {
                $data[] = [
                    'rw_id' => $rwId,
                    'nama_rt' => 'RT 00' . $i,
                    'no_rt' => $i,
                    'alamat' => $faker->streetAddress,
                    'no_telpon' => $faker->optional()->phoneNumber,
                    'jumlah_kk' => 0,
                    'saldo' => $faker->randomFloat(2, 100000, 10000000),
                    'status' => 'aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('rts')->insert($data);
    }
}
