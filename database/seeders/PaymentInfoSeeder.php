<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PaymentInfoSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $rtIds = DB::table('rts')->pluck('id');

        if ($rtIds->isEmpty()) {
            $this->command->warn('Tidak ada RT tersedia, seeder PaymentInfo dilewati.');
            return;
        }

        foreach ($rtIds as $rtId) {
            DB::table('payment_infos')->insert([
                'rt_id' => $rtId,
                'bank_name' => 'Bank BRI',
                'bank_account_number' => '1234567890' . rand(10, 99),
                'bank_account_name' => 'Kas RT ' . $rtId,
                'dana_number' => '0812' . rand(10000000, 99999999),
                'gopay_number' => '0813' . rand(10000000, 99999999),
                'ovo_number' => '0814' . rand(10000000, 99999999),
                'shopeepay_number' => '0815' . rand(10000000, 99999999),
                'qr_code_path' => null,
                'qr_code_description' => 'QR Code pembayaran kas RT',
                'payment_notes' => 'Gunakan salah satu metode pembayaran yang tersedia.',
                'is_active' => true,
                'dana_account_name' => 'Kas Dana RT ' . $rtId,
                'ovo_account_name' => 'Kas OVO RT ' . $rtId,
                'gopay_account_name' => 'Kas GoPay RT ' . $rtId,
                'shopeepay_account_name' => 'Kas ShopeePay RT ' . $rtId,
                'qr_code_account_name' => 'Kas QR RT ' . $rtId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
