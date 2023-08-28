<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class address extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'phoneNumber'=>$this->phone_number,
            'streetName'=>$this->street_name,
            'region'=>$this->region->name,
            'regionID'=>$this->region->id,
            'country'=>$this->region->country->name,
            'countryID'=>$this->region->country->id

        ];
    }
}
