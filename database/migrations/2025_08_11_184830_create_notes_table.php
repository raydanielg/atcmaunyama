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
        if (!Schema::hasTable('notes')) {
            Schema::create('notes', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->longText('body')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        } else {
            Schema::table('notes', function (Blueprint $table) {
                if (!Schema::hasColumn('notes', 'title')) {
                    $table->string('title')->after('id');
                }
                if (!Schema::hasColumn('notes', 'body')) {
                    $table->longText('body')->nullable()->after('title');
                }
                if (!Schema::hasColumn('notes', 'subject_id')) {
                    $table->unsignedBigInteger('subject_id')->nullable()->after('body');
                }
                if (!Schema::hasColumn('notes', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
