<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpnameItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'wirehouse_opname_item';
    public function wirehouse(): BelongsTo
    {
        return $this->belongsTo(Wirehouse::class, 'id_wirehouse');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    static function Qty($month, $year, $id_product)
    {
        $opname = self::where('id_product', $id_product)
            ->where('month', $month)
            ->where('year', $year)
            ->first();
        return $opname ? $opname->qty : 0;
    }
    static function QtyRetail($month, $year, $id_product)
    {
        $opname = self::where('id_product', $id_product)
            ->where('month', $month)
            ->where('year', $year)
            ->first();
        return $opname ? $opname->qty_retail : 0;
    }
    static function QtyReal($month, $year, $id_product)
    {
        $opname = self::where('id_product', $id_product)
            ->where('month', $month)
            ->where('year', $year)
            ->first();
        return $opname ? $opname->qty_real : 0;
    }
    static function QtyRealRetail($month, $year, $id_product)
    {
        $opname = self::where('id_product', $id_product)
            ->where('month', $month)
            ->where('year', $year)
            ->first();
        return $opname ? $opname->qty_real_retail : 0;
    }

    static function description($month, $year, $id_product)
    {
        $opname = self::where('id_product', $id_product)
            ->where('month', $month)
            ->where('year', $year)
            ->first();
        return $opname ? $opname->description : '-';
    }
    static function selisih($month, $year, $id_product)
    {
        $opname = self::where('id_product', $id_product)
            ->where('month', $month)
            ->where('year', $year)
            ->first();
        return $opname ? $opname->selisih : '-';
    }
    static function selisihRetail($month, $year, $id_product)
    {
        $opname = self::where('id_product', $id_product)
            ->where('month', $month)
            ->where('year', $year)
            ->first();
        return $opname ? $opname->selisih_retail : '-';
    }
    static function staff($month, $year, $id_product)
    {
        $opname = self::where('id_product', $id_product)
            ->where('month', $month)
            ->where('year', $year)
            ->first();
        return $opname ? $opname->user->name : '-';
    }
}
