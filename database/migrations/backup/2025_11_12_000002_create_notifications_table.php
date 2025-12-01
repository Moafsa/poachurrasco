<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('establishment_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            
            // Notification Information
            $table->string('type'); // order_created, order_status_changed, review_received, etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data for the notification
            
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

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};


