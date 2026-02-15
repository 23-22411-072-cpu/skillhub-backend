<?php

namespace Database\Factories;

use App\Models\ProviderProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProviderProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
       
        $providerUserId = User::where('role', 'provider')
            ->whereDoesntHave('profile')
            ->inRandomOrder()
            ->first()
            ?->id;

        
        if (!$providerUserId) {
            $providerUserId = User::factory()->create(['role' => 'provider'])->id;
        }

        return [
            'user_id' => $providerUserId,
            'description' => $this->faker->sentence(10),
            'hourly_rate' => $this->faker->numberBetween(1000, 5000), // Random rate
            'experience_years' => $this->faker->numberBetween(1, 15), // Random experience
            
            'location_id' => $this->faker->numberBetween(1, 5), 
            'availability_status' => $this->faker->randomElement(['available', 'busy', 'offline']),
        ];
    }
}