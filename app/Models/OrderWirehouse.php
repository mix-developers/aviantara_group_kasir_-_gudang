<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderWirehouse extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function wirehouse(): BelongsTo
    {
        return $this->belongsTo(Wirehouse::class, 'id_wirehouse');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
    public function order_wirehouse_item()
    {
        return $this->hasMany(OrderWirehouseItem::class, 'id_order_wirehouse');
    }
    public function order_wirehouse_retail_item()
    {
        return $this->hasMany(OrderWirehouseRetailItem::class, 'id_order_wirehouse');
    }
}
