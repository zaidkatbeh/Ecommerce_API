<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cartItem extends Model
{
    use HasFactory;
    protected $fillable=[
      'cart_id',
      'product_id',
      'price',
      'quantity'
    ];
    public function product(){
        return $this->belongsTo(product::class,'product_id','id');
    }
    public function cart(){
        return $this->belongsTo(cart::class);
    }
}
