<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('category_subcategory')) {
            Schema::create('category_subcategory', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('subcategory_id');
                $table->primary(['category_id', 'subcategory_id']);
                $table->index('category_id');
                $table->index('subcategory_id');
            });
        }

        // Backfill existing one-to-many links into the pivot
        if (Schema::hasTable('subcategories')) {
            // Insert current category_id -> subcategory id pairs
            $rows = DB::table('subcategories')->select('id as subcategory_id', 'category_id')->whereNotNull('category_id')->get();
            foreach ($rows as $r) {
                // Guard against nulls and duplicates
                if (!$r->category_id) { continue; }
                $exists = DB::table('category_subcategory')
                    ->where('category_id', $r->category_id)
                    ->where('subcategory_id', $r->subcategory_id)
                    ->exists();
                if (!$exists) {
                    DB::table('category_subcategory')->insert([
                        'category_id' => $r->category_id,
                        'subcategory_id' => $r->subcategory_id,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('category_subcategory');
    }
};
