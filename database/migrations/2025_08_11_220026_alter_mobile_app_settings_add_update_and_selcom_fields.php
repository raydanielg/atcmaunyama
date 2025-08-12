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
        Schema::table('mobile_app_settings', function (Blueprint $table) {
            // Update forcing/grace period
            $table->timestamp('app_update_force_after')->nullable()->after('app_update_notes');

            // Selcom payments (provider is fixed to selcom)
            $table->string('selcom_merchant_id')->nullable()->after('premium_currency');
            $table->string('selcom_api_key')->nullable()->after('selcom_merchant_id');
            $table->string('selcom_env', 16)->nullable()->after('selcom_api_key'); // sandbox|production
            $table->string('selcom_callback_url')->nullable()->after('selcom_env');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mobile_app_settings', function (Blueprint $table) {
            $table->dropColumn([
                'app_update_force_after',
                'selcom_merchant_id',
                'selcom_api_key',
                'selcom_env',
                'selcom_callback_url',
            ]);
        });
    }
};
