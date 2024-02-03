<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
