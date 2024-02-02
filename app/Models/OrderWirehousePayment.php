<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderWirehousePayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_order_wirehouse',
        'id_payment_method',
        'id_user',
        'paid',
    ];
    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'id_payment_method');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function order_wirehouse(): BelongsTo
    {
        return $this->belongsTo(OrderWirehouse::class, 'id_order_wirehouse');
    }
}
