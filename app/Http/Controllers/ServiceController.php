<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\Service; 
use Illuminate\Support\Facades\Validator;
class ServiceController extends Controller
{
    
    public function store(Request $request)
    {
        // 1. Data Validation: 
        $request->validate([
            
            'service_name' => 'required|string|max:100|unique:services,service_name', 
        ]);

        // 2. saving data in database
        $service = Service::create([
            'service_name' => $request->service_name,
        ]);

        // 3. return  Response 
        return response()->json([
            'message' => 'Service created successfully.',
            'service' => $service
        ], 201); 
    }

    /**
     *  Find All service categories 
     */
    public function index()
    {
        
        $services = Service::all(); 
        
        
        return response()->json($services, 200); 
    }

    
    /**
     * Display the specified resource (single service) by ID (READ - Single).
     */
    public function show($id)
    {
        // Find service by its id
        $service = Service::find($id);

        if (!$service) {
            
            return response()->json(['message' => 'Service not found.'], 404);
        }

        
        return response()->json([
            'message' => 'Service fetched successfully.',
            'service' => $service
        ], 200);
    }

    

    
    public function adminStore(Request $request)
    {
        // 1. Role Check
        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized access. Only admin can manage services.'], 403);
        }

        // 2. Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:services,service_name',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0', 
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation Failed.', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // 3.  Create Service
        try {
            $service = Service::create([
                'service_name' => $data['name'],
                'description' => $data['description'] ?? null,
                'base_price' => $data['base_price'],
            ]);

            return response()->json([
                'message' => 'Service created successfully.',
                'service' => $service
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create service.', 'error' => $e->getMessage()], 500);
        }
    }
}