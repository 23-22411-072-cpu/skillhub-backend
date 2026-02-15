<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; 

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id'); 
            
            
            $table->foreignId('booking_id'); 
            $table->unique('booking_id'); 
            
            $table->foreignId('customer_id')
                  ->constrained('users')
                  ->onDelete('restrict');
                  
            
            $table->foreignId('provider_id')
                  ->constrained('users') 
                  ->onDelete('restrict');

            // --- Data Columns ---
            $table->unsignedTinyInteger('rating')->comment('Rating out of 5');
            $table->text('comment')->nullable();
            
            $table->timestamps();
            
            // Indexing for faster lookups
            $table->index(['provider_id', 'rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};