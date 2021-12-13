<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product\SubSubCategoryResource;

class SubCategoryResource extends JsonResource
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
         'sub_category_name'        => $this->sub_category_name,
         'sub_category_slug'        => str_replace(' ', '-', $this->sub_category_name),         
         'sub_category_native_name' => $this->sub_category_native_name,
         'category_id'              => $this->category_id,
         'image'                    => url('images/sub_category/icon/').'/'.$this->icon,
         'status_text'              => $this->status == 1 ? 'Active' : 'Inactive',
         'status'                   => $this->status,
         'home_view'                => $this->home_view,
         'category'                 => $this->whenLoaded('category'),
         'sub_sub_category'         => SubSubCategoryResource::collection($this->whenLoaded('sub_sub_category')),
        ];
    }
}
