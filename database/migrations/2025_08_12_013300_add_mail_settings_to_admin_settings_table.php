<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('admin_settings')) {
            Schema::table('admin_settings', function (Blueprint $table) {
                $table->string('mail_host')->nullable();
                $table->unsignedInteger('mail_port')->nullable();
                $table->string('mail_username')->nullable();
                $table->string('mail_password')->nullable();
                $table->string('mail_encryption')->nullable();
                $table->string('mail_from_address')->nullable();
                $table->string('mail_from_name')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('admin_settings')) {
            Schema::table('admin_settings', function (Blueprint $table) {
                $table->dropColumn([
                    'mail_host','mail_port','mail_username','mail_password','mail_encryption','mail_from_address','mail_from_name'
                ]);
            });
        }
    }
};
