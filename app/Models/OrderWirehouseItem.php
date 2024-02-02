<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderWirehouseItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_order_wirehouse',
        'id_product',
        'expired_date',
        'quantity',
        'subtotal',
    ];
}
