<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('packages') && !Schema::hasColumn('packages', 'seasonal_group_pricing')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->json('seasonal_group_pricing')->nullable()->after('group_pricing_tiers');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('packages') && Schema::hasColumn('packages', 'seasonal_group_pricing')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->dropColumn('seasonal_group_pricing');
            });
        }
    }
};
