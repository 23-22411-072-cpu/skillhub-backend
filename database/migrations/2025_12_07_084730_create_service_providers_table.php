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
        Schema::create('service_providers', function (Blueprint $table) {
            
            
            $table->unsignedBigInteger('provider_id')->primary(); 

            // 2. Data Columns
            $table->integer('experience_years');
            $table->text('skills'); 
            $table->string('price_range', 50);
            $table->float('rating')->default(0);
            $table->text('bio')->nullable();

            // 3. Foreign Key to locations table
            $table->foreignId('location_id')
                  ->constrained('locations', 'location_id') 
                  ->onDelete('restrict');

           
            $table->foreign('provider_id')
                  ->references('id') 
                  ->on('users')
                  ->onDelete('cascade'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_providers');
    }
};