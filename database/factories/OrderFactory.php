<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User; 
use App\Models\Service; 
use App\Models\Location; 
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        // Randomly select valid IDs
        $customerIds = User::where('role', 'customer')->pluck('id')->toArray();
        $providerIds = User::where('role', 'provider')->pluck('id')->toArray();

        
        $customerId = $this->faker->randomElement($customerIds);
        $providerId = $this->faker->randomElement($providerIds);
        $serviceId = Service::inRandomOrder()->first()->service_id ?? 1;
        $locationId = Location::inRandomOrder()->first()->location_id ?? 1;

        $statuses = ['pending', 'accepted', 'in_progress', 'completed', 'cancelled'];

        return [
            'customer_id' => $customerId,
            'provider_user_id' => $providerId,
            'service_id' => $serviceId,
            'location_id' => $locationId,
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 year'),
            'customer_address' => $this->faker->address,
            'notes' => $this->faker->optional(0.5)->sentence,
            'status' => $this->faker->randomElement($statuses), 
            'total_price' => $this->faker->randomFloat(2, 500, 5000), 
        ];
    }
}