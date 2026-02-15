<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User; 
use App\Models\ProviderProfile; 
use App\Models\ProviderService; 
use App\Models\Order; 
use App\Models\Review; // Review Model ko import kiya
use Database\Seeders\ReviewSeeder; // ReviewSeeder ko import kiya

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Starting SkillHub Smart Seeding Protocol...');

        // === 1. POORE SESSION KE LIYE FOREIGN KEY CHECKS DISABLE KARNA ===
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); 
        
        // 🚨 TABLES KO CLEAN KARNA (ORDERS TABLE CHHOD KAR)
        DB::table('provider_profiles')->truncate(); 
        DB::table('provider_services')->truncate(); 
        // 🚨 Reviews table ko bhi truncate kar dena chahiye taaki data consistent rahe
        DB::table('reviews')->truncate(); 
        DB::table('service_providers')->truncate(); 
        
        // 🔥 FIX: Saare users ko clean kar do, taaki CustomerSeeder aur ProviderSeeder naye users banayein (Password 111111 ke saath)
        DB::table('users')->truncate(); 
        
        $this->command->info('Old data cleaned. All tables truncated (except Orders).');
        
        // === 2. ZAROORI STATIC DATA SEED KARNA ===
        $this->call([
            AdminUserSeeder::class, // Admin ab ID 1 se banega
            LocationSeeder::class, 
            ServiceSeeder::class, 
        ]);
        
        // NOTE: ID 5 aur ID 6 ki manual updates ki zaroorat ab nahi hai, kyunki Customer/Provider Seeder in users ko create kar denge.
        
        // === 3. FAKER SE BANA HUA DATA DAALNA ===
        $this->call([
            CustomerSeeder::class, // Naye Customers (Password 111111)
            ProviderSeeder::class, // Naye Providers (Password 111111)
        ]);
        
        // === 4. NAYE 50 RANDOM ORDERS ADD KARNA ===
        Order::factory(50)->create(); 

        // === 5. REVIEWS SEED KARNA (NAYE COMPLETED ORDERS PAR) ===
        $this->call(ReviewSeeder::class); 
        
        $this->command->info('Smart Seeding Complete! Data is ready.');
        
        // === 6. FOREIGN KEY CHECKS DOBARA ENABLE KARNA ===
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); 
    }
}