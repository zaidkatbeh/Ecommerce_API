<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class account_verification_code extends Model
{
    use HasFactory;
    protected $fillable=[
      'user_id',
      'token',
      'created_at',
    ];
    public function isValid(){
     return !!Carbon::now()->lt($this->created_at->addMinutes(3));
    }
}
