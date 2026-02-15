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
        Schema::create('provider_services', function (Blueprint $table) {
            $table->id(); // Standard Primary Key for the pivot table
            
            // --- Foreign Keys ---
          
            $table->foreignId('provider_id')
                  ->constrained('service_providers', 'provider_id') 
                  ->onDelete('cascade');
                  
            
            $table->foreignId('service_id')
                  ->constrained('services', 'service_id')
                  ->onDelete('cascade');

            
            $table->unique(['provider_id', 'service_id']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_services');
    }
};