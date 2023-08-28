<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class cartItemResource extends JsonResource
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
          'price'=>$this->price,
          'name'=>$this->product->name,
          'description'=>$this->product->description,
          'quantity'=>$this->quantity,
          'category'=>$this->product->category->name,
          'added_at'=>$this->updated_at,
          'image'=>$this->product->image!=null?asset("images/products_images/".$this->product->image->image_path):null
//          'image_link'=>($this->product->image)->image_link

        ];
    }
}
