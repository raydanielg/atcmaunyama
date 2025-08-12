<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('school_classes')) {
            Schema::table('school_classes', function (Blueprint $table) {
                if (!Schema::hasColumn('school_classes', 'name')) {
                    $table->string('name')->after('id');
                }
                if (!Schema::hasColumn('school_classes', 'subject_id')) {
                    // SQLite doesn't easily add foreign keys after creation; add as unsigned big integer
                    $table->unsignedBigInteger('subject_id')->after('name');
                    $table->index('subject_id');
                }
                if (!Schema::hasColumn('school_classes', 'description')) {
                    $table->text('description')->nullable()->after('subject_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('school_classes')) {
            Schema::table('school_classes', function (Blueprint $table) {
                if (Schema::hasColumn('school_classes', 'description')) {
                    try { $table->dropColumn('description'); } catch (\Throwable $e) {}
                }
                if (Schema::hasColumn('school_classes', 'subject_id')) {
                    try { $table->dropIndex(['subject_id']); } catch (\Throwable $e) {}
                    try { $table->dropColumn('subject_id'); } catch (\Throwable $e) {}
                }
                if (Schema::hasColumn('school_classes', 'name')) {
                    try { $table->dropColumn('name'); } catch (\Throwable $e) {}
                }
            });
        }
    }
};
