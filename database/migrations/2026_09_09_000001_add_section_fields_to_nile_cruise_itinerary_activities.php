<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nile_cruise_itinerary_activities', function (Blueprint $table) {
            $table->json('section_title')->nullable()->after('attraction_id');
            $table->json('section_description')->nullable()->after('section_title');
        });
    }

    public function down(): void
    {
        Schema::table('nile_cruise_itinerary_activities', function (Blueprint $table) {
            $table->dropColumn(['section_title', 'section_description']);
        });
    }
};
