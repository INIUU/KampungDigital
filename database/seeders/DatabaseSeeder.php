<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // Import wilayah Indonesia SQL dump (MySQL dump)
    DB::unprepared(file_get_contents(database_path('seeders/wilayah_indonesia.sql')));

    // Core seeders - order matters due to foreign keys and dependencies
    $this->call([
      // Location and administrative data
      DesaSeeder::class,
      RwSeeder::class,
      RtSeeder::class,
      KkSeeder::class,
      PendudukSeeder::class,

      // Create users per role and assign leadership/penduduk links
      AdminSeeder::class,
      KadesSeeder::class,
      RwUserSeeder::class,
      RtUserSeeder::class,
      MasyarakatSeeder::class,

      // System settings and payment info
      PengaturanKasSeeder::class,
      PaymentInfoSeeder::class,
      KasSeeder::class,

      // Other application data
      BantuanProposalSeeder::class,
      NotifikasiSeeder::class,
    ]);
  }
}
