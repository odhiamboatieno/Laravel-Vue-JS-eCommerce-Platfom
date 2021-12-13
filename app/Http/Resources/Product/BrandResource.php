<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);


        return [
            
         'id' => $this->id,
         'brand_name' => $this->brand_name,
         'brand_native_name' => $this->brand_native_name,
         'status' => $this->status,
         'image' => url('images/brand/logo/').'/'.$this->brand_logo,
         'status_text' => $this->status == 1 ? 'Active' : 'Inactive',

        ];
    }
}
