<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('packages') || !Schema::hasColumn('packages', 'destination_id')) {
            return;
        }

        // package_cities is the authoritative source for the primary city.
        if (Schema::hasTable('package_cities')) {
            DB::statement(<<<'SQL'
                UPDATE packages p
                INNER JOIN (
                    SELECT pc.package_id,
                           COALESCE(
                               MAX(CASE WHEN pc.is_primary = 1 THEN pc.city_id END),
                               SUBSTRING_INDEX(GROUP_CONCAT(pc.city_id ORDER BY pc.stop_order, pc.id), ',', 1)
                           ) AS city_id
                    FROM package_cities pc
                    GROUP BY pc.package_id
                ) mapped ON mapped.package_id = p.id
                SET p.destination_id = mapped.city_id
            SQL);
        }

        // Older rows stored an attraction id here. Convert rows without a
        // package_cities entry to the city owning that attraction.
        if (Schema::hasTable('attractions')) {
            $missingPivotClause = Schema::hasTable('package_cities')
                ? 'AND NOT EXISTS (SELECT 1 FROM package_cities pc WHERE pc.package_id = p.id)'
                : '';

            DB::statement(<<<SQL
                UPDATE packages p
                INNER JOIN attractions a ON a.id = p.destination_id
                SET p.destination_id = a.city_id
                WHERE p.destination_id IS NOT NULL
                  {$missingPivotClause}
            SQL);
        }

        DB::table('packages')
            ->whereNotNull('destination_id')
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('cities')
                    ->whereColumn('cities.id', 'packages.destination_id');
            })
            ->update(['destination_id' => null]);

        Schema::table('packages', function (Blueprint $table) {
            $table->foreign('destination_id', 'packages_destination_id_foreign')
                ->references('id')
                ->on('cities')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('packages') && Schema::hasColumn('packages', 'destination_id')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->dropForeign('packages_destination_id_foreign');
            });
        }
    }
};
