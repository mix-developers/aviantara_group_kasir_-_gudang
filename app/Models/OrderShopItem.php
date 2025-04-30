<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderShopItem extends Model
{
    use HasFactory;
    protected $table = 'order_shop_items';
    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(ShopProduct::class, 'id_product');
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderShop::class, 'id_order_shop');
    }
}