<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $data = [];

        $province = DB::table('reg_provinces')->inRandomOrder()->first();
        if (!$province) {
            $this->command->warn('Data wilayah belum diimpor. Seeder Desa dilewati.');
            return;
        }

        for ($i = 0; $i < 5; $i++) {
            $province = DB::table('reg_provinces')->inRandomOrder()->first();
            $regency = DB::table('reg_regencies')->where('province_id', $province->id)->inRandomOrder()->first();
            $district = DB::table('reg_districts')->where('regency_id', $regency->id)->inRandomOrder()->first();
            $village = DB::table('reg_villages')->where('district_id', $district->id)->inRandomOrder()->first();

            $data[] = [
                'province_id' => $province->id,
                'regency_id' => $regency->id,
                'district_id' => $district->id,
                'village_id' => $village->id,
                'alamat' => $faker->streetAddress,
                'kode_pos' => $faker->numberBetween(10000, 99999),
                'no_telpon' => $faker->optional()->phoneNumber,
                'gmail' => "desa{$i}@kampungdigital.test",
                'saldo' => $faker->randomFloat(2, 100000, 10000000),
                'status' => 'aktif',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('desas')->insert($data);
    }
}
