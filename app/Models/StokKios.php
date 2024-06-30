<?php

namespace App\Models;

use App\Models\Shop;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokKios extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'stok_kios';

    public function shop(){
        return $this->belongsTo(Shop::class, 'id_kios');
    }

    /**
     * Get the product that owns the StokKios
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
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
