<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidated initial schema for POA Churrasco application
     */
    public function up(): void
    {
        // Users table (extended) - Add fields if they don't exist
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'phone')) {
                    $table->string('phone')->nullable()->after('email');
                }
                if (!Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable()->after('phone');
                }
                if (!Schema::hasColumn('users', 'role')) {
                    $table->enum('role', ['user', 'establishment', 'admin'])->default('user')->after('avatar');
                }
                if (!Schema::hasColumn('users', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('role');
                }
                if (!Schema::hasColumn('users', 'google_id')) {
                    $table->string('google_id')->nullable()->after('is_active');
                }
            });
        }

        // Establishments table (complete with all fields)
        if (!Schema::hasTable('establishments')) {
            Schema::create('establishments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                
                // Basic Information
                $table->string('name');
                $table->string('slug')->unique();
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
                $table->decimal('external_rating', 3, 2)->nullable();
                $table->integer('external_review_count')->default(0);
                $table->integer('view_count')->default(0);
                
                // Business Hours & Services
                $table->json('opening_hours')->nullable();
                $table->json('payment_methods')->nullable();
                $table->json('amenities')->nullable();
                
                // Media
                $table->json('images')->nullable();
                $table->string('logo')->nullable();
                $table->string('cover_image')->nullable();
                $table->json('photo_urls')->nullable();
                
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
                
                // External API Integration Fields
                $table->string('external_id')->nullable()->unique();
                $table->string('external_source')->nullable();
                $table->json('external_data')->nullable();
                $table->timestamp('last_synced_at')->nullable();
                $table->boolean('is_external')->default(false);
                $table->boolean('is_merged')->default(false);
                $table->string('place_id')->nullable();
                $table->string('business_status')->nullable();
                $table->json('types')->nullable();
                $table->decimal('price_level', 2, 1)->nullable();
                $table->json('photos')->nullable();
                $table->json('reviews_external')->nullable();
                $table->string('vicinity')->nullable();
                $table->string('formatted_address')->nullable();
                $table->string('formatted_phone_number')->nullable();
                $table->string('international_phone_number')->nullable();
                $table->json('opening_hours_external')->nullable();
                $table->boolean('permanently_closed')->default(false);
                $table->decimal('user_ratings_total', 8, 0)->nullable();
                
                $table->timestamps();
                
                // Indexes
                $table->index(['status', 'is_featured']);
                $table->index(['category', 'status']);
                $table->index(['latitude', 'longitude']);
                $table->index(['subscription_plan', 'subscription_status']);
                $table->index('is_verified');
                $table->index(['external_id', 'external_source']);
                $table->index(['is_external', 'last_synced_at']);
                $table->index('place_id');
                $table->index('business_status');
            });
        }

        // Products table
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('establishment_id')->constrained()->onDelete('cascade');
                
                // Basic Information
                $table->string('name');
                $table->text('description')->nullable();
                $table->enum('category', [
                    'carnes',
                    'temperos',
                    'acessorios',
                    'bebidas',
                    'sobremesas',
                    'vegetais',
                    'frutas',
                    'laticinios',
                    'outros'
                ]);
                $table->string('subcategory')->nullable();
                
                // Pricing
                $table->decimal('price', 10, 2);
                $table->decimal('compare_price', 10, 2)->nullable();
                $table->decimal('cost_price', 10, 2)->nullable();
                
                // Product Details
                $table->string('sku')->nullable();
                $table->string('barcode')->nullable();
                $table->decimal('weight', 8, 3)->nullable();
                $table->json('dimensions')->nullable();
                
                // Brand & Origin
                $table->string('brand')->nullable();
                $table->string('origin')->nullable();
                
                // Product Information
                $table->json('ingredients')->nullable();
                $table->json('nutritional_info')->nullable();
                $table->json('allergens')->nullable();
                $table->text('storage_instructions')->nullable();
                $table->date('expiry_date')->nullable();
                
                // Product Type
                $table->boolean('is_digital')->default(false);
                $table->boolean('is_service')->default(false);
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_active')->default(true);
                
                // Inventory
                $table->integer('stock_quantity')->default(0);
                $table->integer('low_stock_threshold')->default(5);
                $table->boolean('track_stock')->default(true);
                $table->boolean('allow_backorder')->default(false);
                
                // Media
                $table->json('images')->nullable();
                $table->json('videos')->nullable();
                
                // Tags & SEO
                $table->json('tags')->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->integer('seo_score')->default(0);
                
                // Analytics
                $table->integer('view_count')->default(0);
                $table->integer('purchase_count')->default(0);
                
                // Ratings
                $table->decimal('rating', 3, 2)->default(0);
                $table->integer('review_count')->default(0);
                
                $table->timestamps();
                
                // Indexes
                $table->index(['establishment_id', 'is_active']);
                $table->index(['category', 'is_active']);
                $table->index(['is_featured', 'is_active']);
                $table->index('price');
                $table->index('rating');
                $table->index('view_count');
                $table->index('purchase_count');
                $table->index('sku');
                $table->index('barcode');
            });
        }

        // Promotions table
        if (!Schema::hasTable('promotions')) {
            Schema::create('promotions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('establishment_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->enum('promotion_type', ['percentage', 'fixed'])->default('percentage');
                $table->decimal('discount_value', 10, 2);
                $table->decimal('minimum_order_value', 10, 2)->nullable();
                $table->string('promo_code')->nullable()->unique();
                $table->unsignedInteger('usage_limit')->nullable();
                $table->unsignedInteger('usage_count')->default(0);
                $table->enum('status', ['draft', 'scheduled', 'active', 'paused', 'expired'])->default('draft');
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->json('applicable_products')->nullable();
                $table->json('channels')->nullable();
                $table->boolean('is_stackable')->default(false);
                $table->boolean('is_featured')->default(false);
                $table->string('banner_image')->nullable();
                $table->text('terms')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['establishment_id', 'status']);
                $table->index(['starts_at', 'ends_at']);
                $table->index('promo_code');
            });
        }

        // Services table
        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->foreignId('establishment_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->enum('category', [
                    'bbq_full',
                    'bbq_premium',
                    'bbq_express',
                    'bbq_events',
                    'bbq_delivery',
                    'bbq_class',
                    'bbq_consulting',
                    'grill_installation',
                    'equipment_maintenance',
                    'custom'
                ])->default('bbq_full');
                $table->unsignedInteger('duration_minutes')->nullable();
                $table->unsignedInteger('capacity')->nullable();
                $table->decimal('price', 10, 2);
                $table->decimal('setup_fee', 10, 2)->nullable();
                $table->boolean('includes_meat')->default(false);
                $table->boolean('includes_staff')->default(true);
                $table->boolean('includes_equipment')->default(true);
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_active')->default(true);
                $table->json('images')->nullable();
                $table->json('tags')->nullable();
                $table->json('addons')->nullable();
                $table->json('service_hours')->nullable();
                $table->json('metadata')->nullable();
                $table->decimal('rating', 3, 2)->default(0);
                $table->unsignedInteger('review_count')->default(0);
                $table->unsignedInteger('view_count')->default(0);
                $table->timestamps();

                $table->index(['establishment_id', 'is_active']);
                $table->index(['category', 'is_active']);
                $table->index('slug');
            });
        }

        // Recipes table
        if (!Schema::hasTable('recipes')) {
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

        // Videos table
        if (!Schema::hasTable('videos')) {
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

        // Reviews table
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('establishment_id')->constrained()->onDelete('cascade');
                $table->integer('rating');
                $table->text('comment')->nullable();
                $table->json('images')->nullable();
                $table->boolean('is_verified')->default(false);
                $table->timestamps();
                
                $table->unique(['user_id', 'establishment_id']);
            });
        }

        // External Reviews table
        if (!Schema::hasTable('external_reviews')) {
            Schema::create('external_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('establishment_id')->constrained()->onDelete('cascade');
                $table->string('external_id')->unique();
                $table->string('external_source')->default('google_places');
                $table->string('author_name');
                $table->string('author_url')->nullable();
                $table->string('profile_photo_url')->nullable();
                $table->integer('rating');
                $table->text('text')->nullable();
                $table->timestamp('time');
                $table->string('language')->nullable();
                $table->json('original_data')->nullable();
                $table->boolean('is_verified')->default(true);
                $table->timestamps();
                
                $table->index(['establishment_id', 'rating']);
                $table->index(['external_source', 'external_id']);
                $table->index('time');
            });
        }

        // Favorites table
        if (!Schema::hasTable('favorites')) {
            Schema::create('favorites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('establishment_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['user_id', 'establishment_id']);
            });
        }

        // Orders table
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('establishment_id')->constrained()->onDelete('cascade');
                
                // Order Information
                $table->string('order_number')->unique();
                $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'])->default('pending');
                $table->enum('type', ['pickup', 'delivery', 'dine_in'])->default('pickup');
                
                // Customer Information
                $table->string('customer_name');
                $table->string('customer_email')->nullable();
                $table->string('customer_phone');
                $table->text('customer_address')->nullable();
                $table->string('delivery_address')->nullable();
                
                // Order Details
                $table->json('items');
                $table->decimal('subtotal', 10, 2);
                $table->decimal('delivery_fee', 10, 2)->default(0);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('total', 10, 2);
                
                // Payment Information
                $table->enum('payment_method', ['cash', 'credit_card', 'debit_card', 'pix', 'other'])->nullable();
                $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
                $table->string('payment_reference')->nullable();
                
                // Timestamps
                $table->timestamp('confirmed_at')->nullable();
                $table->timestamp('preparing_at')->nullable();
                $table->timestamp('ready_at')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->timestamp('cancelled_at')->nullable();
                $table->text('cancellation_reason')->nullable();
                
                // Notes
                $table->text('customer_notes')->nullable();
                $table->text('internal_notes')->nullable();
                
                // Audit
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Notifications table
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('establishment_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
                
                // Notification Information
                $table->string('type');
                $table->string('title');
                $table->text('message');
                $table->json('data')->nullable();
                
                // Delivery Channels
                $table->boolean('sent_email')->default(false);
                $table->boolean('sent_push')->default(false);
                $table->boolean('sent_sms')->default(false);
                $table->timestamp('email_sent_at')->nullable();
                $table->timestamp('push_sent_at')->nullable();
                $table->timestamp('sms_sent_at')->nullable();
                
                // Status
                $table->boolean('is_read')->default(false);
                $table->timestamp('read_at')->nullable();
                
                // Timestamps
                $table->timestamps();
            });
        }

        // BBQ Guides table
        if (!Schema::hasTable('bbq_guides')) {
            Schema::create('bbq_guides', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->string('meat_type');
                $table->string('cut_type');
                $table->text('seasoning');
                $table->text('preparation_method');
                $table->text('equipment_needed');
                $table->text('cooking_time');
                $table->text('temperature_guide');
                $table->integer('calories_per_100g')->nullable();
                $table->text('tips');
                $table->text('serving_suggestions');
                $table->string('difficulty_level');
                $table->integer('servings')->nullable();
                $table->json('ingredients')->nullable();
                $table->json('steps')->nullable();
                $table->string('image_url')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->timestamps();
            });
        }

        // BBQ Chats table
        if (!Schema::hasTable('bbq_chats')) {
            Schema::create('bbq_chats', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('session_id');
                $table->text('message');
                $table->text('response');
                $table->json('context')->nullable();
                $table->boolean('is_ai_message')->default(false);
                $table->timestamps();
            });
        }

        // System Settings table
        if (!Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('string');
                $table->text('description')->nullable();
                $table->timestamps();
                
                $table->index('key');
            });
        }

        // Site Contents table
        if (!Schema::hasTable('site_contents')) {
            Schema::create('site_contents', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->string('label');
                $table->text('content')->nullable();
                $table->string('type')->default('text'); // text, html, image, etc.
                $table->string('page')->nullable(); // home, about, contact, etc.
                $table->string('section')->nullable(); // hero, footer, etc.
                $table->json('metadata')->nullable(); // Additional data
                $table->timestamps();
            });
        }

        // Hero Sections table
        if (!Schema::hasTable('hero_sections')) {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_media');
        Schema::dropIfExists('hero_sections');
        Schema::dropIfExists('site_contents');
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('bbq_chats');
        Schema::dropIfExists('bbq_guides');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('external_reviews');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('services');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('products');
        Schema::dropIfExists('establishments');
    }
};

