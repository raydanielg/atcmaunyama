<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('school_class_subject')) {
            Schema::create('school_class_subject', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
                $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['school_class_id','subject_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('school_class_subject');
    }
};
