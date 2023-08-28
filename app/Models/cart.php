<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cart extends Model
{
    use HasFactory;
    protected $fillable=[
        'product_id',
        'user_id'
    ];
    public function product(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function items(){
        return $this->hasMany(cartItem::class);
    }
}
