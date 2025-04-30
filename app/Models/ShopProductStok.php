<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProductStok extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'product_shop_stoks';

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'id_kios');
    }

    /**
     * Get the product that owns the StokKios
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'id_product');
    }

    /**
     * Get the user that owns the StokKios
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}