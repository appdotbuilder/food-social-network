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
        Schema::create('food_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_list_id')->constrained()->onDelete('cascade');
            $table->foreignId('food_product_id')->constrained()->onDelete('cascade');
            $table->text('note')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->unique(['food_list_id', 'food_product_id']);
            $table->index(['food_list_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_list_items');
    }
};