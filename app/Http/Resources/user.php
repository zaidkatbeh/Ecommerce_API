<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class user extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id'=>$this->id,
            'userName'=>$this->name,
            'profile_picture'=>asset('images/profile_pictures'.$this->profile_picture),
            'email'=>$this->email,
            'account_activate'=>!!$this->email_verified_at,
            'Auth_token'=>$this->token?$this->token->plainTextToken:null
        ];
    }
}
