<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->foreignId('voucher_id')->nullable()->after('flight_id')->constrained('vouchers')->nullOnDelete();
            $table->decimal('discount_amount', 12, 2)->default(0)->after('addons_total');
            $table->timestamp('payment_expires_at')->nullable()->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropForeign(['voucher_id']);
            $table->dropColumn(['voucher_id', 'discount_amount', 'payment_expires_at']);
        });
    }
};
