<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class address extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'phone_number',
        'street_name',
        'region_id'
    ];
    public function region(){
        return $this->hasOne(region::class,'id','region_id');
    }
}
