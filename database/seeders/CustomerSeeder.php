<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
// 🔥 FIX 1: Hash Facade zaroor import karen
use Illuminate\Support\Facades\Hash; 

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();
        
        // 20 Naye Customers banana
        for ($i = 0; $i < 20; $i++) {
            User::create([
                'full_name' => $faker->name,
                // Ek specific user bhi bana lete hain test karne ke liye
                'email' => $i === 0 ? 'test_customer@example.com' : $faker->unique()->safeEmail,
                // 🔥 FIX 2: Sabka password '111111' set kiya (using Hash::make)
                'password' => Hash::make('111111'), 
                'role' => 'customer',
                'phone' => $faker->unique()->phoneNumber,
            ]);
        }
    }
}