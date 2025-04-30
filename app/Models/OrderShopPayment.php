<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderShopPayment extends Model
{
    use HasFactory;
    protected $table = 'order_shop_payments';
    protected $guarded = [];
    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'id_payment_method');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function order_shop(): BelongsTo
    {
        return $this->belongsTo(OrderShop::class, 'id_order_shop');
    }
}