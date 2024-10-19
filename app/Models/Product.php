<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    static function getStok($id)
    {
        $stok_masuk =  ProductStok::where('id_product', $id)->select('quantity')->where('type', 'Masuk')->sum('quantity');
        $stok_keluar =  ProductStok::where('id_product', $id)->select('quantity')->where('type', 'Keluar')->sum('quantity');
        $rusak =  ProductDamaged::where('id_product', $id)->sum('quantity_unit');

        $total_stok = $stok_masuk - $stok_keluar - $rusak;
        return $total_stok;
    }

    public function wirehouse(): BelongsTo
    {
        return $this->belongsTo(Wirehouse::class, 'id_wirehouse');
    }
    public function product_stoks()
    {
        return $this->hasMany(ProductStok::class, 'id_product');
    }
    public function product_prices()
    {
        return $this->hasMany(ProductPrice::class, 'id_product');
    }
}
