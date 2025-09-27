<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Disable FK checks for MySQL to avoid errors on missing constraints
        try { DB::statement('SET FOREIGN_KEY_CHECKS=0'); } catch (\Throwable $e) {}

        // 1) materials: drop legacy foreign keys/columns if present
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                // Try to drop foreign key constraints by common names first
                try { $table->dropForeign(['sub_subcategory_id']); } catch (\Throwable $e) {}
                try { $table->dropForeign(['category_id']); } catch (\Throwable $e) {}

                // Also try dropping by constraint name directly
                try { DB::statement("ALTER TABLE materials DROP FOREIGN KEY materials_sub_subcategory_id_foreign"); } catch (\Throwable $e) {}
                try { DB::statement("ALTER TABLE materials DROP FOREIGN KEY materials_category_id_foreign"); } catch (\Throwable $e) {}

                // Try alternative constraint names that Laravel might generate
                try { DB::statement("ALTER TABLE materials DROP FOREIGN KEY IF EXISTS materials_sub_subcategory_id_foreign"); } catch (\Throwable $e) {}
                try { DB::statement("ALTER TABLE materials DROP FOREIGN KEY IF EXISTS materials_category_id_foreign"); } catch (\Throwable $e) {}

                // Try dropping by index name as well
                try { DB::statement("ALTER TABLE materials DROP INDEX IF EXISTS materials_sub_subcategory_id_foreign"); } catch (\Throwable $e) {}
                try { DB::statement("ALTER TABLE materials DROP INDEX IF EXISTS materials_category_id_foreign"); } catch (\Throwable $e) {}

                // SQLite requires dropping indexes before columns; do it via raw just in case
                try { DB::statement('DROP INDEX IF EXISTS materials_category_id_index'); } catch (\Throwable $e) {}
                try { DB::statement('DROP INDEX IF EXISTS materials_sub_subcategory_id_index'); } catch (\Throwable $e) {}

                if (Schema::hasColumn('materials', 'sub_subcategory_id')) {
                    // Drop column directly; FK checks are disabled
                    $table->dropColumn('sub_subcategory_id');
                }
                if (Schema::hasColumn('materials', 'category_id')) {
                    // Drop column directly; FK checks are disabled
                    $table->dropColumn('category_id');
                }
            });
        }

        // 2) subcategories: drop link to categories if present
        if (Schema::hasTable('subcategories') && Schema::hasColumn('subcategories', 'category_id')) {
            Schema::table('subcategories', function (Blueprint $table) {
                // Drop foreign key constraint first
                try { $table->dropForeign(['category_id']); } catch (\Throwable $e) {}
                try { DB::statement('DROP INDEX IF EXISTS subcategories_category_id_index'); } catch (\Throwable $e) {}
                // Drop column directly; FK checks are disabled
                $table->dropColumn('category_id');
            });
        }

        // 3) drop pivot table category_subcategory if exists
        if (Schema::hasTable('category_subcategory')) {
            Schema::drop('category_subcategory');
        }

        // 4) drop material subjects table
        if (Schema::hasTable('sub_subcategories')) {
            Schema::drop('sub_subcategories');
        }

        // 5) drop material levels table (categories)
        if (Schema::hasTable('categories')) {
            Schema::drop('categories');
        }

        // Re-enable FK checks
        try { DB::statement('SET FOREIGN_KEY_CHECKS=1'); } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        // Recreate minimal structures to allow rollback (without data restoration)
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('icon')->nullable();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('category_subcategory')) {
            Schema::create('category_subcategory', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('subcategory_id');
                $table->primary(['category_id','subcategory_id']);
            });
        }
        if (!Schema::hasTable('sub_subcategories')) {
            Schema::create('sub_subcategories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('subcategory_id');
                $table->integer('year')->nullable();
                $table->string('icon')->nullable();
                $table->timestamps();
            });
        }
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                if (!Schema::hasColumn('materials', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('title');
                }
                if (!Schema::hasColumn('materials', 'sub_subcategory_id')) {
                    $table->unsignedBigInteger('sub_subcategory_id')->nullable()->after('subcategory_id');
                }
            });
        }
        if (Schema::hasTable('subcategories')) {
            Schema::table('subcategories', function (Blueprint $table) {
                if (!Schema::hasColumn('subcategories', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('name');
                    $table->index('category_id');
                }
            });
        }
    }
};
