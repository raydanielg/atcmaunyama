<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('notes') && !Schema::hasColumn('notes', 'semister_id')) {
            Schema::table('notes', function (Blueprint $table) {
                $table->unsignedBigInteger('semister_id')->nullable()->after('class_id');
                $table->foreign('semister_id')->references('id')->on('semisters')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notes') && Schema::hasColumn('notes', 'semister_id')) {
            Schema::table('notes', function (Blueprint $table) {
                try { $table->dropForeign(['semister_id']); } catch (\Throwable $e) {}
                $table->dropColumn('semister_id');
            });
        }
    }
};
