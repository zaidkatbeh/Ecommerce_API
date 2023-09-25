<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class cart extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=[
        'product_id',
        'user_id',
        'deleted_at'
    ];
    public function product(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function items(){
        return $this->hasMany(cartItem::class);
    }
}
