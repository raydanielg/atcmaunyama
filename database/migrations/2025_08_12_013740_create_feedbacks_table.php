<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('feedbacks')) {
            Schema::create('feedbacks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // recipient
                $table->foreignId('from_user_id')->nullable()->constrained('users')->nullOnDelete(); // sender (optional)
                $table->string('subject')->nullable();
                $table->text('message');
                $table->unsignedTinyInteger('rating')->nullable(); // 1..5
                $table->string('status')->default('new'); // new, read, archived
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
