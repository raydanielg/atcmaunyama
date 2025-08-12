<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('activity_logs')) {
            if (!Schema::hasColumn('activity_logs', 'action')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->string('action')->nullable()->after('id');
                });
            }
            if (!Schema::hasColumn('activity_logs', 'description')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->text('description')->nullable()->after('action');
                });
            }
            if (!Schema::hasColumn('activity_logs', 'causer_id')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->unsignedBigInteger('causer_id')->nullable()->after('description');
                });
            }
            if (!Schema::hasColumn('activity_logs', 'ip_address')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->string('ip_address', 45)->nullable()->after('causer_id');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('activity_logs')) {
            // Optional: drop columns if they exist
            foreach (['ip_address','causer_id','description','action'] as $col) {
                if (Schema::hasColumn('activity_logs', $col)) {
                    Schema::table('activity_logs', function (Blueprint $table) use ($col) {
                        $table->dropColumn($col);
                    });
                }
            }
        }
    }
};
