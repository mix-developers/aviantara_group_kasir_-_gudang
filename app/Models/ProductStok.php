<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStok extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    static function getAllStok($month, $year)
    {
        $date = \Carbon\Carbon::create($year, $month, 1);

        $stok_masuk = ProductStok::where('created_at', '<', $date)
            ->where('type', 'Masuk')
            ->sum('quantity');

        $stok_keluar = ProductStok::where('created_at', '<', $date)
            ->where('type', 'Keluar')
            ->sum('quantity');

        $rusak = ProductDamaged::where('created_at', '<', $date)
            ->sum('quantity_unit');

        $stok = $stok_masuk - $stok_keluar - $rusak;

        return $stok;
    }
    public function product_stoks()
    {
        return $this->hasMany(ProductStok::class, 'product_id');
    }
}