<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wirehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'logo',
        'ud_cv',
    ];

    static function getProduct($id)
    {
        $product = Product::where('id_wirehouse', $id)->count();
        return $product;
    }
}