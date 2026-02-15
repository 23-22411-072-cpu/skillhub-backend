<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location; 
use Illuminate\Support\Facades\Validator; 
class LocationController extends Controller
{
    // Saving location in database
    public function store(Request $request)
    {
        // Validation rules
        $request->validate([
            'city' => 'required|string|max:50',
            'area' => 'required|string|max:100|unique:locations,area', 
        ]);

        $location = Location::create([
            'city' => $request->city,
            'area' => $request->area,
        ]);

        return response()->json([
            'message' => 'Location created successfully.',
            'location' => $location
        ], 201);
    }
    
    //locations (READ - All)
    public function index()
    {
        $locations = Location::all();
        return response()->json($locations, 200);
    }
    
    
    public function show($id)
    {
        // find location by its priamary key
        $location = Location::find($id);

        if (!$location) {
            return response()->json(['message' => 'Location not found.'], 404);
        }

        return response()->json([
            'message' => 'Location fetched successfully.',
            'location' => $location
        ], 200);
    }
    
    //  delete Location 
    public function destroy($id)
    {
        $location = Location::find($id);

        if (!$location) {
            return response()->json(['message' => 'Location not found.'], 404);
        }

        $location->delete();

        return response()->json(['message' => 'Location deleted successfully.'], 200);
    }

    
     
    public function adminStore(Request $request)
    {
        // 1. Role Check: 
        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized access. Only admin can manage locations.'], 403);
        }

        
        $validator = Validator::make($request->all(), [
           'city' => 'required|string|max:50',
            
            'area' => 'required|string|max:100|unique:locations,area',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation Failed.', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // 3.  Create Location
        try {
            
            $location = Location::create([
                'city' => $data['city'],
                'area' => $data['area'],
            ]);

            return response()->json([
                'message' => 'Location created successfully (Admin).',
                'location' => $location
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create location.', 'error' => $e->getMessage()], 500);
        }
    }
}