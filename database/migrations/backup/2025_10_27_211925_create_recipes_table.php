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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establishment_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->text('description')->nullable();
            $table->enum('category', [
                'beef',
                'pork',
                'poultry',
                'seafood',
                'sides',
                'desserts',
                'drinks',
                'other'
            ])->default('beef');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->unsignedInteger('prep_time_minutes')->nullable();
            $table->unsignedInteger('cook_time_minutes')->nullable();
            $table->unsignedInteger('rest_time_minutes')->nullable();
            $table->unsignedInteger('servings')->default(4);
            $table->json('ingredients');
            $table->json('instructions');
            $table->json('tips')->nullable();
            $table->json('images')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('favorite_count')->default(0);
            $table->json('nutrition_facts')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['establishment_id', 'is_published']);
            $table->index(['category', 'difficulty']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
