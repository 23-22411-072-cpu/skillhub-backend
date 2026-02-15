<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderService extends Model
{
    use HasFactory;
    
    
    protected $table = 'service_providers';
    
   
    protected $primaryKey = 'provider_id';
    public $incrementing = false;
    protected $keyType = 'int';
    
    protected $fillable = [
        'provider_id', 
        'experience_years',
        'skills', 
        'price_range', 
        'rating',
        'bio', 
        'location_id'
       
    ];
}