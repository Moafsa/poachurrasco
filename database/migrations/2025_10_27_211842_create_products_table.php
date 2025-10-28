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
            $table->decimal('weight', 8, 3)->nullable(); // in kg
            $table->json('dimensions')->nullable(); // length, width, height
            
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};