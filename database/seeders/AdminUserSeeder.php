<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Naya Admin user create karna.
     */
    public function run(): void
    {
        // Pehle check karen ki Admin user maujood hai ya nahi
        if (!User::where('email', 'admin@skillhub.com')->exists()) {
            User::create([
                'full_name' => 'SkillHub Admin',
                'email' => 'admin@skillhub.com',
                'password' => Hash::make('password'), // Password: password
                'phone' => '00000000000',
                'role' => 'admin',
                
            ]);
            $this->command->info('Admin user created: admin@skillhub.com / password');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}