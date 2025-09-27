<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Disable FK checks for MySQL
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        /**
         * 1) Clean materials table
         */
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                if (Schema::hasColumn('materials', 'sub_subcategory_id')) {
                    $table->dropForeign(['sub_subcategory_id']);
                    $table->dropIndex(['sub_subcategory_id']);
                    $table->dropColumn('sub_subcategory_id');
                }
                if (Schema::hasColumn('materials', 'category_id')) {
                    $table->dropForeign(['category_id']);
                    $table->dropIndex(['category_id']);
                    $table->dropColumn('category_id');
                }
            });
        }

        /**
         * 2) Clean subcategories table
         */
        if (Schema::hasTable('subcategories') && Schema::hasColumn('subcategories', 'category_id')) {
            Schema::table('subcategories', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->dropIndex(['category_id']);
                $table->dropColumn('category_id');
            });
        }

        /**
         * 3) Drop pivot and lookup tables
         */
        if (Schema::hasTable('category_subcategory')) {
            Schema::dropIfExists('category_subcategory');
        }
        if (Schema::hasTable('sub_subcategories')) {
            Schema::dropIfExists('sub_subcategories');
        }
        if (Schema::hasTable('categories')) {
            Schema::dropIfExists('categories');
        }

        // Re-enable FK checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // Recreate categories table
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('icon')->nullable();
                $table->timestamps();
            });
        }

        // Recreate pivot table
        if (!Schema::hasTable('category_subcategory')) {
            Schema::create('category_subcategory', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('subcategory_id');
                $table->primary(['category_id','subcategory_id']);
            });
        }

        // Recreate sub_subcategories table
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

        // Add back columns in materials
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                if (!Schema::hasColumn('materials', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('title');
                    $table->index('category_id');
                }
                if (!Schema::hasColumn('materials', 'sub_subcategory_id')) {
                    $table->unsignedBigInteger('sub_subcategory_id')->nullable()->after('subcategory_id');
                    $table->index('sub_subcategory_id');
                }
            });
        }

        // Add back category_id in subcategories
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
