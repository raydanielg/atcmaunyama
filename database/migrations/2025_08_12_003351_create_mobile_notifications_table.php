<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('mobile_notifications')) {
            return;
        }
        Schema::create('mobile_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title', 120);
            $table->text('message');
            $table->string('deep_link')->nullable();
            $table->timestamp('scheduled_at')->nullable()->index();
            $table->enum('repeat', ['none','hourly','daily','weekly','monthly'])->default('none');
            $table->enum('status', ['queued','scheduled','sending','sent','failed','canceled'])->default('queued')->index();
            $table->timestamp('sent_at')->nullable();
            $table->unsignedInteger('delivered_count')->default(0);
            $table->unsignedInteger('opened_count')->default(0);
            $table->unsignedInteger('clicked_count')->default(0);
            $table->json('targets')->nullable();
            $table->json('meta')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_notifications');
    }
};
