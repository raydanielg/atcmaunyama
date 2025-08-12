<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notes')) {
            Schema::table('notes', function (Blueprint $table) {
                if (!Schema::hasColumn('notes', 'level_id')) $table->unsignedBigInteger('level_id')->nullable()->after('user_id');
                if (!Schema::hasColumn('notes', 'class_id')) $table->unsignedBigInteger('class_id')->nullable()->after('level_id');
                if (!Schema::hasColumn('notes', 'file_path')) $table->string('file_path')->nullable()->after('body');
                if (!Schema::hasColumn('notes', 'original_name')) $table->string('original_name')->nullable()->after('file_path');
                if (!Schema::hasColumn('notes', 'mime_type')) $table->string('mime_type')->nullable()->after('original_name');
                if (!Schema::hasColumn('notes', 'file_size')) $table->unsignedBigInteger('file_size')->nullable()->after('mime_type');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('notes')) {
            Schema::table('notes', function (Blueprint $table) {
                if (Schema::hasColumn('notes', 'level_id')) $table->dropColumn('level_id');
                if (Schema::hasColumn('notes', 'class_id')) $table->dropColumn('class_id');
                if (Schema::hasColumn('notes', 'file_path')) $table->dropColumn('file_path');
                if (Schema::hasColumn('notes', 'original_name')) $table->dropColumn('original_name');
                if (Schema::hasColumn('notes', 'mime_type')) $table->dropColumn('mime_type');
                if (Schema::hasColumn('notes', 'file_size')) $table->dropColumn('file_size');
            });
        }
    }
};
