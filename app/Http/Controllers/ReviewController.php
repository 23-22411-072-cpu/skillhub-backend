<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Review; 
use App\Models\User; 
use App\Models\ProviderProfile; 
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    
    public function submitReview(Request $request)
    {
        // 1. Validation Rules
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id', 
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $data = $validator->validated();

        // 2. Authorization & Role Check
        if ($user->role !== 'customer') {
            return response()->json(['message' => 'Unauthorized: Only customers can submit reviews.'], 403);
        }

        // 3. Order Fetch and Checks
        $order = Order::find($data['order_id']);
        
        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        if ($order->customer_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized: This order was not placed by you.'], 403);
        }
        
        if ($order->status !== 'completed') { 
             return response()->json(['message' => 'Review can only be submitted for completed orders. Current status: ' . $order->status], 400);
        }

        // Verification:  if review exists already
        if (Review::where('order_id', $order->id)->exists()) { 
            return response()->json(['message' => 'A review for this order already exists.'], 409);
        }

        // 4. Create Review 
        try {
            $review = Review::create([
                'order_id' => $order->id, 
                'customer_id' => $user->id,
                'provider_id' => $order->provider_user_id,
                'rating' => $data['rating'],
                'comment' => $data['comment'],
            ]);

            // 5. Provider's Average Rating 
            $providerId = $order->provider_user_id;

            // Average rating and total count
            $averageRating = Review::where('provider_id', $providerId)->avg('rating');
            $reviewCount = Review::where('provider_id', $providerId)->count();

            //  update using ProviderProfile model 
            ProviderProfile::where('user_id', $providerId)->update([
                'average_rating' => round($averageRating, 2), 
                'review_count' => $reviewCount
            ]);
           

            return response()->json([
                'message' => 'Review submitted successfully.',
                'review' => $review
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to submit review due to internal error.', 'error' => $e->getMessage()], 500);
        }
    }

    
    // fetching all reviews and ratings of a specific providers
    public function getProviderReviews($provider_id)
    {
        // 1. Provider existence check
        $provider = User::where('id', $provider_id)
                        ->where('role', 'provider')
                        ->first();

        if (!$provider) {
            return response()->json(['message' => 'Provider not found.'], 404);
        }

        // 2. fetch Reviews by using customer details
        
        $reviews = Review::with('customer:id,full_name,phone') 
                         ->where('provider_id', $provider_id)
                         ->orderBy('created_at', 'desc')
                         ->get();
                         
        // 3. Average rating from ProviderProfile table
        $profile = ProviderProfile::where('user_id', $provider_id)->first();
        
        $averageRating = $profile ? $profile->average_rating : 0.0;
        $reviewCount = $reviews->count();

        return response()->json([
            'provider_name' => $provider->full_name,
            'average_rating' => $averageRating,
            'review_count' => $reviewCount,
            'reviews' => $reviews


        
        ]);
    }
}