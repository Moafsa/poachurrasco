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
        Schema::create('hero_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page')->unique(); // home, about, contact, etc.
            $table->string('type')->default('image'); // image, video, slideshow
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->string('primary_button_text')->nullable();
            $table->string('primary_button_link')->nullable();
            $table->string('secondary_button_text')->nullable();
            $table->string('secondary_button_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->json('settings')->nullable(); // Additional settings like autoplay, loop, etc.
            $table->timestamps();
        });

        Schema::create('hero_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hero_section_id')->constrained()->onDelete('cascade');
            $table->string('type'); // image, video
            $table->string('media_path'); // Path to the file
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->integer('display_order')->default(0);
            $table->string('alt_text')->nullable();
            $table->json('metadata')->nullable(); // Width, height, duration, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_media');
        Schema::dropIfExists('hero_sections');
    }
};




















