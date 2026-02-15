<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Service; // Service Model ko import karna zaroori hai

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['service_name' => 'Carpenter'], 
            ['service_name' => 'Electrician'], // Space hata diya taaki consistency rahe
            ['service_name' => 'Plumber'],     
            ['service_name' => 'Beautician'],  
            ['service_name' => 'Tutor'],       
        ];

        foreach ($services as $service) {
            // FIX: updateOrCreate use karen taaki agar service pehle se maujood ho 
            // toh woh dobara create na ho aur duplicate na bane.
            Service::updateOrCreate(
                // Search condition (service_name unique honi chahiye)
                ['service_name' => $service['service_name']], 
                // Values to update/create
                $service
            );
        }
        
        $this->command->info('✅ Services seeded successfully (using updateOrCreate).');
    }
}