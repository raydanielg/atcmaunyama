<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                if (!Schema::hasColumn('materials', 'level_id')) {
                    $table->unsignedBigInteger('level_id')->nullable()->after('subcategory_id');
                }
                if (!Schema::hasColumn('materials', 'subject_id')) {
                    $table->unsignedBigInteger('subject_id')->nullable()->after('level_id');
                }
                if (!Schema::hasColumn('materials', 'class_id')) {
                    $table->unsignedBigInteger('class_id')->nullable()->after('subject_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                if (Schema::hasColumn('materials', 'class_id')) {
                    $table->dropColumn('class_id');
                }
                if (Schema::hasColumn('materials', 'subject_id')) {
                    $table->dropColumn('subject_id');
                }
                if (Schema::hasColumn('materials', 'level_id')) {
                    $table->dropColumn('level_id');
                }
            });
        }
    }
};
