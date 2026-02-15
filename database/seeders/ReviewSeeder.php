<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Review;
use App\Models\ProviderProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pehle se maujood reviews ko delete nahi karenge.
        
        // 1. Un orders ko uthaao jo 'completed' hain aur jin par abhi tak review nahi hua.
        $completedOrders = Order::where('status', 'completed')
                                ->whereDoesntHave('review') // Order model mein 'review' relationship hona zaroori hai
                                ->get();

        $reviewsToSeed = 50;
        $orderCount = $completedOrders->count();
        $seededCount = 0;

        if ($orderCount == 0) {
            $this->command->info('No completed orders available for seeding reviews.');
            return;
        }

        $this->command->info('Seeding ' . min($reviewsToSeed, $orderCount) . ' reviews...');

        // 2. Reviews create karna
        foreach ($completedOrders->take($reviewsToSeed) as $order) {
            // Random rating (1 se 5)
            $rating = rand(1, 5); 

            $review = Review::create([
                'order_id' => $order->id, 
                'customer_id' => $order->customer_id,
                'provider_id' => $order->provider_user_id,
                'rating' => $rating,
                'comment' => "Random review for service provided on Order #" . $order->id . " with a $rating star rating."
            ]);
            $seededCount++;
        }

        // 3. Sabhi Providers ki Average Rating aur Review Count Update karna
        
        // Un providers ki IDs nikalna jinhe naye reviews mile hain
        $providerIds = Review::pluck('provider_id')->unique();

        $this->command->info('Updating average ratings for ' . $providerIds->count() . ' providers...');

        foreach ($providerIds as $providerId) {
            // Average rating aur total count nikalna
            $averageRating = Review::where('provider_id', $providerId)->avg('rating');
            $reviewCount = Review::where('provider_id', $providerId)->count();

            // ProviderProfile model ko use karke update karna
            ProviderProfile::where('user_id', $providerId)->update([
                'average_rating' => round($averageRating, 2),
                'review_count' => $reviewCount
            ]);
        }

        $this->command->info($seededCount . ' new reviews successfully added and provider profiles updated.');
    }
}