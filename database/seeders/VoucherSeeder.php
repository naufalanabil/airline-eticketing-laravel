<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        Voucher::updateOrCreate(
            ['code' => 'TIKETMURAH'],
            ['type' => 'fixed', 'amount' => 100000, 'max_uses' => 100, 'valid_until' => '2026-12-31'],
        );

        Voucher::updateOrCreate(
            ['code' => 'PROMO50'],
            ['type' => 'percent', 'amount' => 10, 'max_uses' => 100, 'valid_until' => '2026-12-31'],
        );
    }
}
