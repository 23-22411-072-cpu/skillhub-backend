<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders'; 

    protected $fillable = [
        'customer_id', 
        'provider_user_id',
        'service_id',
        'location_id',
        'scheduled_at',
        'customer_address', 
        'notes', 
        'total_price',
        'status',
        'payment_method',
        'payment_status',
    ];
    
    protected $casts = [
        'scheduled_at' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    
    public function review() {
        
        return $this->hasOne(Review::class, 'order_id');
    }

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider() {
        return $this->belongsTo(User::class, 'provider_user_id');
    }

    public function service() {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    public function location() {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }
}