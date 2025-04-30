<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderShop extends Model
{
    use HasFactory;
    protected $table = 'order_shop';
    protected $guarded = [];
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'id_shop');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
    public function order_shop_item()
    {
        return $this->hasMany(OrderShopItem::class, 'id_order_shop');
    }
    public function order_shop_payment()
    {
        return $this->hasMany(OrderShopPayment::class, 'id_order_shop');
    }
}