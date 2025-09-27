<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('materials') && !Schema::hasColumn('materials', 'sub_subcategory_id')) {
            Schema::table('materials', function (Blueprint $table) {
                $table->unsignedBigInteger('sub_subcategory_id')->nullable()->after('subcategory_id');
                $table->index('sub_subcategory_id');
            });

            // Try add foreign key if both tables exist
            if (Schema::hasTable('sub_subcategories')) {
                try {
                    Schema::table('materials', function (Blueprint $table) {
                        $table->foreign('sub_subcategory_id')
                              ->references('id')
                              ->on('sub_subcategories')
                              ->onDelete('set null');
                    });
                } catch (\Throwable $e) {
                    // ignore for SQLite or if already exists
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('materials') && Schema::hasColumn('materials', 'sub_subcategory_id')) {
            try { DB::statement('ALTER TABLE materials DROP CONSTRAINT materials_sub_subcategory_id_foreign'); } catch (\Throwable $e) {}
            Schema::table('materials', function (Blueprint $table) {
                try { $table->dropForeign(['sub_subcategory_id']); } catch (\Throwable $e) {}
                try { $table->dropIndex(['sub_subcategory_id']); } catch (\Throwable $e) {}
                $table->dropColumn('sub_subcategory_id');
            });
        }
    }
};
