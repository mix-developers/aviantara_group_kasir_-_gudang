<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopProduct extends Model
{
    use HasFactory;
    protected $table = 'product_shop';
    protected $guarded = [];
    public function shops(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'id_shop');
    }
    public function stocks()
    {
        return $this->hasMany(ShopProductStok::class, 'id_product', 'id');
    }
    static function getStok($id, $bulan = null, $tahun = null)
    {
        $stokMasukQuery = ShopProductStok::where('id_product', $id)->where('type', 'Masuk');
        $stokKeluarQuery = ShopProductStok::where('id_product', $id)->where('type', 'Keluar');
        // $rusakQuery = ProductDamaged::where('id_product', $id);

        if ($bulan && $tahun) {
            $tanggal = \Carbon\Carbon::create($tahun, $bulan, 1)->startOfMonth()->subDay();

            $stokMasukQuery->whereDate('created_at', '<=', $tanggal);
            $stokKeluarQuery->whereDate('created_at', '<=', $tanggal);
            // $rusakQuery->whereDate('created_at', '<=', $tanggal);
        }
        //product
        $product = ShopProduct::find($id);

        $stokMasuk = $stokMasukQuery->sum('qty');
        $stokKeluar = $stokKeluarQuery->sum('qty');
        // $rusak = $rusakQuery->sum('quantity_unit');

        $totalStok = ($stokMasuk - $stokKeluar);

        return $totalStok;
    }
    public function latestPrice()
    {
        return $this->hasOne(ShopProductPrice::class, 'id_product')->latest();
    }
}