<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProviderProfile;
use App\Models\ProviderService;

class Service extends Model
{
    use HasFactory;
    
    
    protected $primaryKey = 'service_id'; 

    protected $fillable = [
        'service_name', 
        'description',
        'base_price', 
    ];

    
    public function providerProfiles()
    {
        return $this->belongsToMany(
            ProviderProfile::class,
            'provider_services', // Pivot Table Name
            'service_id',      
            'provider_id'        // Related FK
        )
        ->using(ProviderService::class) 
        ->withPivot('price'); 
    }
}