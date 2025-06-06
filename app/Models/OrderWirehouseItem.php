<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderWirehouseItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderWirehouse::class, 'id_order_wirehouse');
    }
}