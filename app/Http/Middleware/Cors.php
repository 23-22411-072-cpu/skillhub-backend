<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    public function handle(Request $request, Closure $next)
    {
        
        $allowedOrigin = 'http://localhost:5173';
        
        $response = $next($request);

         
        if ($request->isMethod('OPTIONS')) {
            $response = response()->setStatusCode(200);
        }

       
        $response->header('Access-Control-Allow-Origin', $allowedOrigin);
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization, X-Requested-With, X-XSRF-TOKEN'); 
        $response->header('Access-Control-Allow-Credentials', 'true'); 
        
        return $response;
    }
}