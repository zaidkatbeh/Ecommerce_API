<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\prodct_picture;

class product extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'description',
        'price',
        'quantity',
        'category_id',
    ];
    public function image(){
        return $this->hasOne(product_picture::class,'product_id','id');
    }
    public function category()
    {
        return $this->belongsTo(category::class);
    }

}
