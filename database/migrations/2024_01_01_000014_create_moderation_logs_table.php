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
        Schema::create('moderation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('moderator_id')->constrained('users');
            $table->morphs('target'); // The content that was moderated
            $table->string('action'); // 'hide', 'delete', 'restore', 'dismiss_report'
            $table->text('reason');
            $table->json('metadata')->nullable(); // Additional context
            $table->timestamps();
            
            // Indexes
            $table->index(['moderator_id', 'created_at']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderation_logs');
    }
};