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
                if (!Schema::hasColumn('materials', 'title')) {
                    $table->string('title', 200)->after('id');
                }
                if (!Schema::hasColumn('materials', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->index()->after('title');
                }
                if (!Schema::hasColumn('materials', 'subcategory_id')) {
                    $table->unsignedBigInteger('subcategory_id')->nullable()->index()->after('category_id');
                }
                if (!Schema::hasColumn('materials', 'path')) {
                    $table->string('path')->nullable()->after('subcategory_id');
                }
                if (!Schema::hasColumn('materials', 'url')) {
                    $table->string('url', 2048)->nullable()->after('path');
                }
                if (!Schema::hasColumn('materials', 'mime')) {
                    $table->string('mime', 191)->nullable()->after('url');
                }
                if (!Schema::hasColumn('materials', 'size')) {
                    $table->unsignedBigInteger('size')->nullable()->after('mime');
                }
                if (!Schema::hasColumn('materials', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->index()->after('size');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                foreach (['title','category_id','subcategory_id','path','url','mime','size','user_id'] as $col) {
                    if (Schema::hasColumn('materials', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
