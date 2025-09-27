<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->string('email')->nullable()->after('author_name');
            $table->foreign('parent_id')->references('id')->on('blog_comments')->cascadeOnDelete();
            $table->index(['post_id','parent_id']);
        });
    }

    public function down(): void
    {
        Schema::table('blog_comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id','email']);
        });
    }
};
