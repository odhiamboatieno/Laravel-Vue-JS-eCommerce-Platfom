<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class SubSubCategoryResource extends JsonResource
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
                     
         'id'                       => $this->id,
         'sub_sub_category_name'    => $this->sub_sub_category_name,
         'sub_sub_category_slug'    => str_replace(' ', '-', $this->sub_sub_category_name),         
         'sub_sub_category_native_name' => $this->sub_sub_category_native_name,
         'category_id'              => $this->category_id,
         'sub_category_id'          => $this->sub_category_id,
         'image'                    => url('images/sub_sub_category/icon/').'/'.$this->icon,
         'status_text'              => $this->status == 1 ? 'Active' : 'Inactive',
         'status'                   => $this->status,
         'category'                 => $this->whenLoaded('category'),
         'sub_category'             => $this->whenLoaded('sub_category'),
         'sub_sub_category_brand'   => $this->whenLoaded('sub_sub_category_brand'),
        ];
    }
}
