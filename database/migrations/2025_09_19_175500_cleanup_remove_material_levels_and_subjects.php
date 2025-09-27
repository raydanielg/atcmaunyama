<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Disable FK checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // 1) materials cleanup
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                // Drop FK if it exists
                $constraints = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_NAME = 'materials' 
                    AND TABLE_SCHEMA = DATABASE()
                ");
                foreach ($constraints as $constraint) {
                    if ($constraint->CONSTRAINT_NAME === 'materials_sub_subcategory_id_foreign' ||
                        $constraint->CONSTRAINT_NAME === 'materials_category_id_foreign') {
                        try {
                            DB::statement("ALTER TABLE materials DROP FOREIGN KEY {$constraint->CONSTRAINT_NAME}");
                        } catch (\Throwable $e) {}
                    }
                }

                // Drop columns only if they exist
                if (Schema::hasColumn('materials', 'sub_subcategory_id')) {
                    $table->dropColumn('sub_subcategory_id');
                }
                if (Schema::hasColumn('materials', 'category_id')) {
                    $table->dropColumn('category_id');
                }
            });
        }

        // 2) subcategories cleanup
        if (Schema::hasTable('subcategories') && Schema::hasColumn('subcategories', 'category_id')) {
            Schema::table('subcategories', function (Blueprint $table) {
                try {
                    DB::statement("ALTER TABLE subcategories DROP FOREIGN KEY subcategories_category_id_foreign");
                } catch (\Throwable $e) {}
                $table->dropColumn('category_id');
            });
        }

        // 3) drop pivot if exists
        if (Schema::hasTable('category_subcategory')) {
            Schema::drop('category_subcategory');
        }

        // 4) drop sub_subcategories
        if (Schema::hasTable('sub_subcategories')) {
            Schema::drop('sub_subcategories');
        }

        // 5) drop categories
        if (Schema::hasTable('categories')) {
            Schema::drop('categories');
        }

        // Enable FK checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // same as before (restore minimal structure)
    }
};
