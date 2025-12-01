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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
