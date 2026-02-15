<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Review; 
use App\Models\User; 
use App\Mail\OrderStatusUpdatedMail; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OrderController extends Controller
{
    // 1. PROVIDER ORDERS
    public function getProviderOrders(Request $request)
    {
        try {
            $userId = Auth::id();
            if (!$userId && $request->has('provider_email')) {
                $userId = User::where('email', $request->provider_email)->value('id');
            }

            if (!$userId) return response()->json(['error' => 'Unauthorized'], 401);

            $locationId = $request->query('location_id'); 
            $query = Order::with(['customer', 'service', 'location', 'review'])
                ->where('provider_user_id', $userId);

            if ($locationId) {
                $query->where('location_id', $locationId);
            }

            $orders = $query->orderBy('created_at', 'desc')->get();
            return response()->json(['status' => 'success', 'orders' => $orders]);
        } catch (\Exception $e) { 
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // 2. CUSTOMER ORDERS
    public function getCustomerOrders(Request $request) {
        $userId = Auth::id();
        if (!$userId && $request->has('email')) {
            $userId = User::where('email', $request->email)->value('id');
        }

        if (!$userId) return response()->json(['error' => 'Unauthorized'], 401);

        $orders = Order::with(['provider', 'service', 'review'])
            ->where('customer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json(['status' => 'success', 'orders' => $orders]);
    }

    // 3. UPDATE ORDER STATUS
    public function updateOrderStatus(Request $request, $id)
    {
        $userId = Auth::id();
        if (!$userId && $request->has('provider_email')) {
            $userId = User::where('email', $request->provider_email)->value('id');
        }

        $order = Order::where('id', $id)->where('provider_user_id', $userId)->first();
        if (!$order) return response()->json(['message' => 'Order not found'], 404);
        
        $order->status = $request->status;
        if ($request->status === 'completed') { $order->payment_status = 'paid'; }
        $order->save();

        if ($order->status === 'accepted') {
            $customer = User::find($order->customer_id);
            if ($customer) {
                Mail::to($customer->email)->send(new OrderStatusUpdatedMail($order, 'customer'));
            }
        }
        
        return response()->json(['status' => 'success']);
    }

    // 4. SUBMIT RATING
    public function submitRating(Request $request, $id) {
        $userId = Auth::id();
        if (!$userId && $request->has('email')) {
            $userId = User::where('email', $request->email)->value('id');
        }

        $order = Order::where('id', $id)->where('customer_id', $userId)->first();
        if (!$order || $order->status !== 'completed') {
            return response()->json(['message' => 'Error: Order not found or not completed'], 400);
        }

        $review = Review::updateOrCreate(
            ['order_id' => $id],
            [
                'customer_id' => $userId,
                'provider_id' => $order->provider_user_id,
                'rating'      => $request->rating,
                'comment'     => $request->comment,
            ]
        );
        return response()->json(['status' => 'success']);
    }

    // 5. GET PROVIDER REVIEWS 
    public function getProviderReviews($id)
    {
    try {
        $reviews = Review::with('customer')
            ->where('provider_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

       
        return response()->json([
            'status' => 'success',
            'ratings' => $reviews
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
    }

    // 6. STORE ORDER
    public function store(Request $request) {
        $customerId = Auth::id();
        if (!$customerId && $request->has('customer_email')) {
            $customerId = User::where('email', $request->customer_email)->value('id');
        }

        if (!$customerId) {
            return response()->json(['message' => 'Unauthorized: User not found'], 401);
        }

        $order = Order::create([
            'customer_id'      => $customerId,
            'provider_user_id' => $request->provider_user_id,
            'service_id'       => $request->service_id,
            'location_id'      => $request->location_id,
            'scheduled_at'     => Carbon::parse($request->scheduled_at),
            'customer_address' => $request->customer_address,
            'total_price'      => $request->total_price,
            'status'           => 'pending',
            'payment_method'   => 'COD',
            'payment_status'   => 'pending'
        ]);

        $provider = User::find($request->provider_user_id);
        if ($provider) {
            Mail::to($provider->email)->send(new OrderStatusUpdatedMail($order, 'provider'));
        }

        return response()->json(['message' => 'Order created!', 'order' => $order], 201);
    }

    // 7. CANCEL ORDER
    public function cancelOrder($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json([
            'status' => 'success', 
            'message' => 'Order cancelled successfully'
        ]);
    }
}