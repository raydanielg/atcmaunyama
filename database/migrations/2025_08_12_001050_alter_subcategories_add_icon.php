<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('subcategories')) {
            Schema::table('subcategories', function (Blueprint $table) {
                if (!Schema::hasColumn('subcategories', 'icon')) {
                    $table->text('icon')->nullable()->after('name');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('subcategories')) {
            Schema::table('subcategories', function (Blueprint $table) {
                if (Schema::hasColumn('subcategories', 'icon')) {
                    $table->dropColumn('icon');
                }
            });
        }
    }
};
