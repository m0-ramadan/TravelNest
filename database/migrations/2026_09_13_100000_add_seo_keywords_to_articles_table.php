<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('articles', 'seo_keywords')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->text('seo_keywords')->nullable()->after('seo_description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('articles', 'seo_keywords')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->dropColumn('seo_keywords');
            });
        }
    }
};
