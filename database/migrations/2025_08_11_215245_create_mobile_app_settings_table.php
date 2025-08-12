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
        Schema::create('mobile_app_settings', function (Blueprint $table) {
            $table->id();
            // Branding / assets
            $table->string('app_icon_path')->nullable();
            $table->string('splash_image_path')->nullable();
            $table->string('splash_headline')->nullable();
            $table->string('splash_subtext')->nullable();

            // Feature toggles
            $table->boolean('show_notifications')->default(true);

            // App updates
            $table->boolean('app_update_required')->default(false);
            $table->string('app_update_version')->nullable();
            $table->text('app_update_notes')->nullable();

            // OAuth / Google callback
            $table->string('google_callback_url')->nullable();

            // Premium
            $table->boolean('premium_enabled')->default(false);
            $table->string('premium_provider')->nullable(); // e.g., stripe, mpesa
            $table->decimal('premium_price', 10, 2)->nullable();
            $table->string('premium_currency', 10)->nullable();
            $table->json('premium_features')->nullable();

            // Misc
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_app_settings');
    }
};
