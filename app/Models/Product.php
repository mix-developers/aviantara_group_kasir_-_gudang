<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit',
        'barcode',
        'unit',
        'quantity_unit',
        'photo',
        'id_wirehouse',
    ];

    static function getStok($id)
    {
        $stok = ProductStok::where('id_product', $id);
        $stok_masuk = $stok->where('type', 'Masuk')->sum('quantity');
        $stok_keluar = $stok->where('type', 'Keluar')->sum('quantity');

        $total_stok = $stok_masuk - $stok_keluar;
        return $total_stok >= 0 ? $total_stok : 0;
    }

    public function wirehouse(): BelongsTo
    {
        return $this->belongsTo(Wirehouse::class, 'id_wirehouse');
    }
}
