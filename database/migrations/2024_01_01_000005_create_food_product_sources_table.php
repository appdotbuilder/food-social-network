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
        Schema::create('food_product_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_product_id')->constrained()->onDelete('cascade');
            $table->foreignId('source_id')->constrained()->onDelete('cascade');
            $table->string('field_type'); // 'ingredients', 'nutrition', 'allergens', 'certifications'
            $table->timestamps();
            
            // Indexes
            $table->unique(['food_product_id', 'source_id', 'field_type']);
            $table->index('field_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_product_sources');
    }
};