<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProductPrice extends Model
{
    use HasFactory;
    protected $table = 'product_shop_prices';
    protected $guarded = [];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'id_kios');
    }
    public  function product()
    {
        return $this->belongsTo(ShopProduct::class, 'id_product');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}