<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'email', 
        'location',
        'products',
        'total_amount', 
        'order_status',
        'client_ip'
    ];
    
}
