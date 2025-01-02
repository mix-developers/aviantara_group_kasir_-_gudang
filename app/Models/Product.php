<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    static function getStok($id, $bulan = null, $tahun = null)
    {
        $stokMasukQuery = ProductStok::where('id_product', $id)->where('type', 'Masuk');
        $stokKeluarQuery = ProductStok::where('id_product', $id)->where('type', 'Keluar');
        $rusakQuery = ProductDamaged::where('id_product', $id);

        if ($bulan && $tahun) {
            $tanggal = \Carbon\Carbon::create($tahun, $bulan, 1)->startOfMonth()->subDay();

            $stokMasukQuery->whereDate('created_at', '<=', $tanggal);
            $stokKeluarQuery->whereDate('created_at', '<=', $tanggal);
            $rusakQuery->whereDate('created_at', '<=', $tanggal);
        }

        $stokMasuk = $stokMasukQuery->sum('quantity');
        $stokKeluar = $stokKeluarQuery->sum('quantity');
        $rusak = $rusakQuery->sum('quantity_unit');

        $totalStok = $stokMasuk - $stokKeluar - $rusak;

        return $totalStok;
    }
    static function getStokRetail($productId, $bulan = null, $tahun = null)
    {
        $product = Product::find($productId);

        $totalOrderRetailQuery = OrderWirehouseRetailItem::where('id_product', $productId);

        if ($bulan && $tahun) {
            $tanggal = \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth();
            $totalOrderRetailQuery->whereDate('created_at', '<=', $tanggal);
        }
        $totalOrderRetail = $totalOrderRetailQuery->sum('quantity');

        $sisa = $totalOrderRetail % $product->quantity_unit;

        return $sisa;
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
    static function estimateIncomeWirehouse($id_wirehouse, $bulan = null, $tahun = null)
    {

        $products = self::where('id_wirehouse', $id_wirehouse)->get();

        $totalPendapatan = 0;

        foreach ($products as $item) {

            $harga = ProductPrice::where('id_product', $item->id)
                ->orderBy('id', 'DESC')
                ->first();

            $stok = Product::getStok($item->id, $bulan, $tahun);

            $pendapatanPerProduk = ($harga->price_grosir ?? 0) * $stok;

            $totalPendapatan += $pendapatanPerProduk;
        }

        return $totalPendapatan;
    }
    static function estimateIncomeRetailWirehouse($id_wirehouse, $bulan = null, $tahun = null)
    {

        $products = self::where('id_wirehouse', $id_wirehouse)->get();

        $totalPendapatan = 0;

        foreach ($products as $item) {

            $harga = ProductPrice::where('id_product', $item->id)
                ->orderBy('id', 'DESC')
                ->first();

            $stok = OrderWirehouseRetailItem::where('id_product', $item->id);
            $priceGrosir = $harga->price_grosir ?? 0;
            $pendapatanPerProduk = ($priceGrosir / $item->quantity_unit) * $stok->sum('quantity');

            $totalPendapatan += $pendapatanPerProduk;
        }

        return $totalPendapatan;
    }
}
