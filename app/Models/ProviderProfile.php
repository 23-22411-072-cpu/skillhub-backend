<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderProfile extends Model
{
    use HasFactory;
    
    protected $table = 'provider_profiles'; 
    protected $primaryKey = 'id'; 
    public $incrementing = true; 
    
    protected $fillable = [
        'user_id', 
        'description', 
        'hourly_rate', 
        'experience_years',
        'location_id',
        'availability_status',
       
        'average_rating',
        'review_count',
    ];
    
    // Relations remains linked to user_id
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class, 'location_id'); 
    }

    
    public function services()
    {
        
        return $this->belongsToMany(
            \App\Models\Service::class, 
            'provider_services', 
            'provider_id',// foreignPivotKey 
            'service_id',// relatedPivotKey 
            'user_id' 
        )->withPivot('price_range');
    }
    
    
    public function getSkillsAttribute() 
    { 
        return $this->description; 
    }
    
    
    public function reviews()
    {
       
        return $this->hasMany(\App\Models\Review::class, 'provider_id', 'user_id');
    }
}