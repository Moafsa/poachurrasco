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
        Schema::create('establishments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Basic Information
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('state', 2);
            $table->string('zip_code', 10);
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            
            // Location
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Business Information
            $table->enum('category', [
                'churrascaria',
                'açougue',
                'supermercado',
                'restaurante',
                'bar',
                'lanchonete',
                'delivery',
                'outros'
            ]);
            $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])->default('pending');
            $table->boolean('is_featured')->default(false);
            
            // Ratings & Reviews
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('review_count')->default(0);
            
            // Business Hours & Services
            $table->json('opening_hours')->nullable();
            $table->json('payment_methods')->nullable();
            $table->json('amenities')->nullable();
            
            // Media
            $table->json('images')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            
            // Social Media
            $table->json('social_media')->nullable();
            
            // Subscription & Monetization
            $table->enum('subscription_plan', ['free', 'basic', 'premium', 'enterprise'])->default('free');
            $table->enum('subscription_status', ['active', 'inactive', 'expired', 'cancelled'])->default('active');
            $table->timestamp('subscription_expires_at')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(5.00);
            
            // Verification
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verification_date')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'is_featured']);
            $table->index(['category', 'status']);
            $table->index(['latitude', 'longitude']);
            $table->index(['subscription_plan', 'subscription_status']);
            $table->index('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishments');
    }
};