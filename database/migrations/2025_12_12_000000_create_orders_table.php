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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // 1. Customer ID
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            
            // 2. Provider ID
            $table->foreignId('provider_user_id')->constrained('users')->onDelete('cascade');
            
            // 3. Service ID
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('service_id')->on('services')->onDelete('cascade');
            
            // 4. Location ID
            $table->unsignedBigInteger('location_id')->nullable();
            $table->foreign('location_id')->references('location_id')->on('locations')->onDelete('set null');
            
            // Booking Details
            $table->timestamp('scheduled_at'); 
            $table->string('customer_address'); 
            $table->text('notes')->nullable(); 

            // Status and Pricing
            
            $table->enum('status', ['pending', 'accepted', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->decimal('total_price', 8, 2); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};