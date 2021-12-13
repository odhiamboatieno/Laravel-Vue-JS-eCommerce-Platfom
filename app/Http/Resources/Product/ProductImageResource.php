<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
           
           'id' => $this->id,
           'product_id' => $this->product_id,
           'image' => url('images/product/image/').'/'.$this->image_name,
    
        ];
    }
}
