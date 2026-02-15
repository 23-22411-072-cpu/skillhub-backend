<?php

namespace App\Http\Controllers;

use App\Models\ProviderProfile;
use App\Models\ProviderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class ProviderProfileController extends Controller
{
    // 1. Providers Filter List
    public function index(Request $request)
    {
        $serviceId = $request->query('service_id');
        $locationId = $request->query('location_id'); 
        $query = ProviderProfile::with(['services', 'user', 'location']);

        if ($serviceId) {
            $query->whereHas('services', function ($q) use ($serviceId) {
                $q->where('provider_services.service_id', $serviceId); 
            });
        }
        if ($locationId && is_numeric($locationId) && (int)$locationId > 0) {
            $query->where('location_id', (int)$locationId);
        }
        return response()->json(['providers' => $query->get()], 200);
    }
    
    // 2. Profile Setup Logic (Table: provider_profiles)
    public function updateOrCreateProfile(Request $request) 
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'provider') return response()->json(['message' => 'Unauthorized'], 403);

        $request->validate([
            'description' => 'required|string|max:1000',
            'hourly_rate' => 'required|numeric|min:0', 
            'experience_years' => 'required|integer|min:0',
            'location_id' => 'required|integer', 
        ]);

        try {
            // Update profile info
            $profile = ProviderProfile::updateOrCreate(
                ['user_id' => $user->id], 
                [
                    'description' => $request->description,
                    'hourly_rate' => $request->hourly_rate,
                    'experience_years' => $request->experience_years,
                    'location_id' => $request->location_id,
                    'skills' => $request->skills, 
                    'availability_status' => $request->availability_status ?? 'available'
                ]
            );
            
            return response()->json(['message' => 'Basic profile updated!', 'profile' => $profile], 200);
        } catch (\Exception $e) {
            Log::error('Save Profile Error: ' . $e->getMessage()); 
            return response()->json(['message' => 'Error saving profile', 'error' => $e->getMessage()], 500);
        }
    }

    // 3. Link service function
    public function linkServices(Request $request)
    {
        $user = auth()->user();
        //  find Provider Profile using id
        $profile = ProviderProfile::where('user_id', $user->id)->first();
        
        if (!$profile) {
            return response()->json(['message' => 'Profile not found. Please save basic profile first.'], 404);
        }

        try {
            $syncData = [];
            foreach ($request->services as $service) {
                
                $syncData[$service['service_id']] = [
                    'price_range' => (string) $service['price']
                ]; 
            }

            // Pivot table (provider_services) 
            $profile->services()->sync($syncData); 

            return response()->json(['message' => 'Services linked successfully!'], 200);
        } catch (\Exception $e) {
            Log::error('Service Link Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error linking services', 'error' => $e->getMessage()], 500);
        }
    }

    // 4. Get Current Logged-in Provider Profile
    public function getProfile() 
    {
        $profile = ProviderProfile::with('services', 'location')->where('user_id', auth()->id())->first();
        return response()->json(['profile' => $profile], 200);
    }
}