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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
