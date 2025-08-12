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
                if (!Schema::hasColumn('materials', 'slug')) {
                    $table->string('slug', 191)->nullable()->unique()->after('title');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                if (Schema::hasColumn('materials', 'slug')) {
                    $table->dropUnique(['slug']);
                    $table->dropColumn('slug');
                }
            });
        }
    }
};
