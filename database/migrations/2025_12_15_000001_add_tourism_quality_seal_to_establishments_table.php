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
            $table->boolean('has_tourism_quality_seal')->default(false)->after('is_verified');
            $table->timestamp('tourism_quality_seal_date')->nullable()->after('has_tourism_quality_seal');
            $table->enum('tourism_quality_seal_reason', ['vote', 'merit', 'special'])->nullable()->after('tourism_quality_seal_date');
            $table->text('tourism_quality_seal_notes')->nullable()->after('tourism_quality_seal_reason');
        });
        
        // Add index for efficient queries
        Schema::table('establishments', function (Blueprint $table) {
            $table->index('has_tourism_quality_seal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('establishments', function (Blueprint $table) {
            if (Schema::hasColumn('establishments', 'has_tourism_quality_seal')) {
                $table->dropIndex(['has_tourism_quality_seal']);
            }
            $table->dropColumn([
                'has_tourism_quality_seal',
                'tourism_quality_seal_date',
                'tourism_quality_seal_reason',
                'tourism_quality_seal_notes',
            ]);
        });
    }
};

