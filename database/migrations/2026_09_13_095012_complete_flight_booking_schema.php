<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('airlines', 'code')) {
            Schema::table('airlines', function (Blueprint $table) {
                $table->string('code', 10)->unique();
                $table->string('name');
            });
        }

        if (! Schema::hasColumn('airports', 'code')) {
            Schema::table('airports', function (Blueprint $table) {
                $table->string('code', 10)->unique();
                $table->string('name');
                $table->string('city');
                $table->string('country');
            });
        }

        if (! Schema::hasColumn('flights', 'available_seats')) {
            Schema::table('flights', function (Blueprint $table) {
                $table->string('flight_number', 20);
                $table->foreignId('airline_id')->constrained('airlines')->cascadeOnDelete();
                $table->foreignId('origin_airport_id')->constrained('airports')->cascadeOnDelete();
                $table->foreignId('destination_airport_id')->constrained('airports')->cascadeOnDelete();
                $table->dateTime('departure_time');
                $table->dateTime('arrival_time');
                $table->decimal('price', 12, 2);
                $table->integer('total_seats');
                $table->integer('available_seats');
            });
        }

        if (! Schema::hasColumn('bookings', 'booking_code')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('booking_code', 50)->unique();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('flight_id')->constrained('flights')->cascadeOnDelete();
                $table->decimal('total_amount', 12, 2);
                $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
                $table->string('snap_token')->nullable();
                $table->string('pdf_path')->nullable();
            });
        }

        if (! Schema::hasColumn('passengers', 'booking_id')) {
            Schema::table('passengers', function (Blueprint $table) {
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->string('full_name');
                $table->string('passport_number', 50);
                $table->date('passport_expiry');
                $table->string('nationality', 50)->default('Indonesia');
                $table->string('seat_number', 10)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropColumn([
                'booking_id',
                'full_name',
                'passport_number',
                'passport_expiry',
                'nationality',
                'seat_number',
            ]);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['flight_id']);
            $table->dropColumn([
                'booking_code',
                'user_id',
                'flight_id',
                'total_amount',
                'status',
                'snap_token',
                'pdf_path',
            ]);
        });

        Schema::table('flights', function (Blueprint $table) {
            $table->dropForeign(['airline_id']);
            $table->dropForeign(['origin_airport_id']);
            $table->dropForeign(['destination_airport_id']);
            $table->dropColumn([
                'flight_number',
                'airline_id',
                'origin_airport_id',
                'destination_airport_id',
                'departure_time',
                'arrival_time',
                'price',
                'total_seats',
                'available_seats',
            ]);
        });

        Schema::table('airlines', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn(['code', 'name']);
        });

        Schema::table('airports', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn(['code', 'name', 'city', 'country']);
        });
    }
};
