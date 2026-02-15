<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
// ... (Baaki models)
use Illuminate\Support\Facades\Hash;
// ... (Baaki imports)

class ProviderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        // Location IDs ko dynamic tarah se load karna behtar hai agar Location Seeder maujood ho
        $locationIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]; 
        $serviceIds = Service::pluck('service_id')->toArray(); 

        if (empty($serviceIds)) {
            $this->command->error('Error: Services table is empty. Please run ServiceSeeder first.');
            return;
        }

        // Database ko saaf rakhne ke liye sirf 10 providers create karte hain.
        $count = 10; 
        
        $this->command->info("Creating {$count} dummy providers...");

        for ($i = 0; $i < $count; $i++) {
            // 1. User Banana
            $user = User::create([
                'full_name' => $faker->name . ' Services',
                'email' => $faker->unique()->safeEmail,
                
                // 🔥 FIX: Password '111111' set kiya (jo customer ke liye bhi set hai)
                'password' => Hash::make('111111'), 
                
                'role' => 'provider',
                'phone' => $faker->numerify('03#########'),
            ]);
            
            // ... (Baaki profile aur pivot table code wahi rahega)
            // ...
        }
        $this->command->info("✅ {$count} dummy providers successfully created and services attached.");
    }
}