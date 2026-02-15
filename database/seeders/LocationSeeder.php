<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Location; // Location Model ko import karna zaroori hai

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['city' => 'Lahore', 'area' => 'DHA Phase 5'],
            ['city' => 'Lahore', 'area' => 'Gulberg III'],
            ['city' => 'Lahore', 'area' => 'Model Town'],
            ['city' => 'Lahore', 'area' => 'Johar Town'],
            ['city' => 'Lahore', 'area' => 'Bahria Town'],
            ['city' => 'Lahore', 'area' => 'Askari XI'],
            ['city' => 'Lahore', 'area' => 'Ferozepur Road'],
            ['city' => 'Lahore', 'area' => 'Wapda Town'],
            ['city' => 'Lahore', 'area' => 'Cantt (LHR)'],
            ['city' => 'Lahore', 'area' => 'Raiwind Road'],
        ];

        foreach ($locations as $location) {
            // FIX: updateOrCreate use karen taaki agar location pehle se maujood ho 
            // toh woh dobara create na ho aur duplicate na bane.
            Location::updateOrCreate(
                // Search condition (key combination unique honi chahiye)
                ['city' => $location['city'], 'area' => $location['area']], 
                // Values to update/create
                $location
            );
        }
        
        $this->command->info('✅ Locations seeded successfully (using updateOrCreate).');
    }
}