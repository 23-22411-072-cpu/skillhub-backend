<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Service;
use App\Models\Location;

class CleanupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Starting comprehensive database cleanup (excluding Users and Orders)...');

        // 1. FOREIGN KEY CHECKS DISABLE KAREN (Truncate karne ke liye zaroori)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); 

        // 2. DEPENDENT TABLES KO SAF KAREN (Jinko Location/Service IDs ki zaroorat hai)
        DB::table('provider_profiles')->truncate(); 
        DB::table('service_providers')->truncate(); 
        DB::table('provider_services')->truncate(); 
        DB::table('reviews')->truncate(); // Reviews bhi service/provider IDs use karte hain

        $this->command->info('Dependent provider and review data safely truncated.');

        // 3. TARGET TABLES KO SAF KAREN (Jahan duplicates hain)
        Location::truncate();
        Service::truncate();
        
        $this->command->info('Services and Locations tables cleared.');

        // 4. DOBARA SEED KAREN (FIXED LOGIC KE SAATH)
        $this->call(LocationSeeder::class);
        $this->call(ServiceSeeder::class);
        
        // 5. Providers ko DOBARA Create karen (Kyunki profiles delete ho chuki hain)
        // Ye users ko bachate hue naye profiles aur links bana dega.
        $this->call(ProviderSeeder::class); 

        $this->command->info('Database cleaned, re-seeded, and provider profiles re-created.');

        // 6. FOREIGN KEY CHECKS DOBARA ENABLE KAREN
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); 
    }
}