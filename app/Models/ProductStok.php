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
}
