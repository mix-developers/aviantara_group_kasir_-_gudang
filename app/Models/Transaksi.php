<?php

namespace App\Models;

use App\Models\StokKios;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function stok()
    {
        return $this->belongsTo(StokKios::class, 'stok_id');
    }
}
