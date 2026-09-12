<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_items')) {
            $indexes = collect(DB::select('SHOW INDEXES FROM booking_items'))->pluck('Key_name')->all();
            if (in_array('booking_items_booking_id_unique', $indexes, true)) {
                Schema::table('booking_items', function (Blueprint $table) {
                    $table->dropForeign(['booking_id']);
                    $table->dropUnique('booking_items_booking_id_unique');
                    $table->foreign('booking_id')->references('id')->on('bookings')->cascadeOnDelete();
                    $table->index('booking_id');
                });
            }
        }
    }

    public function down(): void
    {
        // No need to restore broken unique constraint
    }
};
