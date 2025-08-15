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
        Schema::create('food_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('ingredients')->nullable();
            $table->json('nutrition_facts')->nullable();
            $table->json('allergens')->nullable();
            $table->json('certifications')->nullable();
            $table->string('manufacturing_location')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->json('barcodes')->nullable();
            $table->text('image_url')->nullable();
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('review_count')->default(0);
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index('name');
            $table->index('brand_id');
            $table->index('country_of_origin');
            $table->index('manufacturing_location');
            $table->index(['is_hidden', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_products');
    }
};