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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establishment_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('category', [
                'tutorial',
                'recipe',
                'event',
                'behind_the_scenes',
                'interview',
                'other'
            ])->default('tutorial');
            $table->string('video_url');
            $table->string('provider')->default('youtube');
            $table->string('provider_video_id')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('share_count')->default(0);
            $table->json('captions')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['establishment_id', 'is_published']);
            $table->index(['category', 'is_published']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
