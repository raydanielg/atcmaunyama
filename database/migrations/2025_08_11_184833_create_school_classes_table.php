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
        if (!Schema::hasTable('school_classes')) {
            Schema::create('school_classes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('level_id')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('school_classes', function (Blueprint $table) {
                if (!Schema::hasColumn('school_classes', 'name')) {
                    $table->string('name')->after('id');
                }
                if (!Schema::hasColumn('school_classes', 'level_id')) {
                    $table->unsignedBigInteger('level_id')->nullable()->after('name');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};
