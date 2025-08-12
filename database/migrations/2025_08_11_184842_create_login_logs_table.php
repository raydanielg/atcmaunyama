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
        if (!Schema::hasTable('login_logs')) {
            Schema::create('login_logs', function (Blueprint $table) {
                $table->id();
                $table->string('email');
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('login_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('login_logs', 'email')) {
                    $table->string('email')->after('id');
                }
                if (!Schema::hasColumn('login_logs', 'ip_address')) {
                    $table->string('ip_address')->nullable()->after('email');
                }
                if (!Schema::hasColumn('login_logs', 'user_agent')) {
                    $table->text('user_agent')->nullable()->after('ip_address');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
};
