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
        Schema::create('external_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establishment_id')->constrained()->onDelete('cascade');
            $table->string('external_id')->unique(); // Google Places review ID
            $table->string('external_source')->default('google_places'); // Source platform
            $table->string('author_name'); // Reviewer name from external platform
            $table->string('author_url')->nullable(); // Reviewer profile URL
            $table->string('profile_photo_url')->nullable(); // Reviewer profile photo
            $table->integer('rating'); // 1-5 stars
            $table->text('text')->nullable(); // Review text
            $table->timestamp('time'); // When the review was posted
            $table->string('language')->nullable(); // Review language
            $table->json('original_data')->nullable(); // Store original API response
            $table->boolean('is_verified')->default(true); // External reviews are considered verified
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['establishment_id', 'rating']);
            $table->index(['external_source', 'external_id']);
            $table->index('time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_reviews');
    }
};
