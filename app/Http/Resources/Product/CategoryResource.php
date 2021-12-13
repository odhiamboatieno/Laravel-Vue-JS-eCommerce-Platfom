<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Product\SubCategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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

            'id'                   => $this->id,
            'category_name'        => $this->category_name,
            'category_slug'        => str_replace(' ', '-', $this->category_name),
            'category_native_name' => $this->category_native_name,
            'status'               => $this->status,
            'image'                => url('images/category/icon/') . '/' . $this->icon,
            'sub_category'         => SubCategoryResource::collection($this->whenLoaded('sub_category')),
            'status_text'          => $this->status == 1 ? 'Active' : 'Inactive',

        ];
    }
}
