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
        Schema::table('establishments', function (Blueprint $table) {
            // External API Integration Fields
            $table->string('external_id')->nullable()->unique()->comment('External API establishment ID');
            $table->string('external_source')->nullable()->comment('Source API (google_places, foursquare, etc.)');
            $table->json('external_data')->nullable()->comment('Raw data from external API');
            $table->timestamp('last_synced_at')->nullable()->comment('Last synchronization with external API');
            $table->boolean('is_external')->default(false)->comment('Whether this establishment comes from external API');
            $table->boolean('is_merged')->default(false)->comment('Whether this establishment was merged with user data');
            
            // Additional fields for better API integration
            $table->string('place_id')->nullable()->comment('Google Places API place_id');
            $table->string('business_status')->nullable()->comment('Business status from API');
            $table->json('types')->nullable()->comment('Business types from API');
            $table->decimal('price_level', 2, 1)->nullable()->comment('Price level from API (0-4)');
            $table->json('photos')->nullable()->comment('Photos from external API');
            $table->json('reviews_external')->nullable()->comment('External reviews data');
            $table->string('vicinity')->nullable()->comment('Vicinity from API');
            $table->string('formatted_address')->nullable()->comment('Formatted address from API');
            $table->string('formatted_phone_number')->nullable()->comment('Formatted phone from API');
            $table->string('international_phone_number')->nullable()->comment('International phone from API');
            $table->json('opening_hours_external')->nullable()->comment('Opening hours from external API');
            $table->boolean('permanently_closed')->default(false)->comment('Whether establishment is permanently closed');
            $table->decimal('user_ratings_total', 8, 0)->nullable()->comment('Total user ratings count');
            
            // Indexes for better performance
            $table->index(['external_id', 'external_source']);
            $table->index(['is_external', 'last_synced_at']);
            $table->index('place_id');
            $table->index('business_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('establishments', function (Blueprint $table) {
            $table->dropIndex(['external_id', 'external_source']);
            $table->dropIndex(['is_external', 'last_synced_at']);
            $table->dropIndex('place_id');
            $table->dropIndex('business_status');
            
            $table->dropColumn([
                'external_id',
                'external_source',
                'external_data',
                'last_synced_at',
                'is_external',
                'is_merged',
                'place_id',
                'business_status',
                'types',
                'price_level',
                'photos',
                'reviews_external',
                'vicinity',
                'formatted_address',
                'formatted_phone_number',
                'international_phone_number',
                'opening_hours_external',
                'permanently_closed',
                'user_ratings_total'
            ]);
        });
    }
};