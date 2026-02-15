<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'customer_id',
        'provider_id', 
        'order_id',
        'rating',
        'comment'
    ];

    // Relationships
    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider() {
        return $this->belongsTo(User::class, 'provider_id');
    }
}