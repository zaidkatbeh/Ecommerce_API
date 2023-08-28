<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class productsResource extends JsonResource
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
            'name'=>$this->name,
            'description'=>$this->description,
            'price'=>$this->price,
            'stock'=>$this->quantity,
            'category_id'=>$this->category_id,
            'date'=>$this->updated_at,
            'image_link'=>$this->transformImage($this->image)


        ];
    }
    private function transformImage($image)
    {
        if (!$image) {
            return null;
        }

        // Custom transformation logic for the image data
            return asset('images/products_images/' . $image->image_path);
            // Add more custom fields or modify existing ones as needed
    }
}
