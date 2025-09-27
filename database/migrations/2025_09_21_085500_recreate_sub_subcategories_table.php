<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sub_subcategories')) {
            Schema::create('sub_subcategories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('subcategory_id');
                $table->integer('year')->nullable();
                $table->string('icon')->nullable();
                $table->timestamps();

                $table->index(['subcategory_id']);
                // For SQLite compatibility, we avoid adding the FK if subcategories is missing
                // but in normal cases subcategories exists, so try to add the FK.
            });
        }

        // Add FK separately to be tolerant across drivers
        if (Schema::hasTable('sub_subcategories') && Schema::hasTable('subcategories')) {
            try {
                Schema::table('sub_subcategories', function (Blueprint $table) {
                    // Guard against duplicate foreign key on some drivers
                    if (method_exists($table, 'foreign')) {
                        $table->foreign('subcategory_id')
                              ->references('id')
                              ->on('subcategories')
                              ->onDelete('cascade');
                    }
                });
            } catch (\Throwable $e) {
                // ignore if FK already exists or not supported
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sub_subcategories')) {
            Schema::drop('sub_subcategories');
        }
    }
};
