<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image_path')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
            $table->index(['created_at']);
        });

        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('blog_posts')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name')->nullable(); // for guests if needed later
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('blog_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('blog_posts')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['like','dislike']);
            $table->timestamps();
            $table->unique(['post_id','user_id','type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_reactions');
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blog_posts');
    }
};
