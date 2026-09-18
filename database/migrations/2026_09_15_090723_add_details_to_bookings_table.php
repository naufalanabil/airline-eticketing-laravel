<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->string('seat_class')->default('Economy')->after('flight_id');
            $table->unsignedInteger('baggage_weight')->default(20)->after('seat_class');
            $table->boolean('has_meal')->default(false)->after('baggage_weight');
            $table->boolean('has_insurance')->default(false)->after('has_meal');
            $table->decimal('addons_total', 12, 2)->default(0)->after('total_amount');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropColumn([
                'seat_class',
                'baggage_weight',
                'has_meal',
                'has_insurance',
                'addons_total',
            ]);
        });
    }
};
