<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Product\BrandResource;
use App\Http\Resources\Product\CategoryResource;
use App\Http\Resources\Product\ProductImageResource;
use App\Http\Resources\Product\SubCategoryResource;
use App\Http\Resources\Product\SubSubCategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
            [

            'id'                  => $this->id,
            'product_name'        => $this->product_name,
            'product_slug'        => str_replace([' ', '/'], '-', $this->product_name),
            'product_native_name' => $this->product_native_name,
            'category_id'         => $this->category_id,
            'sub_category_id'     => $this->sub_category_id,
            'sub_sub_category_id' => $this->sub_sub_category_id,
            'brand_id'            => $this->brand_id,
            'stock_quantity'      => $this->stock_quantity,
            'current_quantity'    => $this->current_quantity,
            'quantity_unit'       => $this->quantity_unit == 'null' ? '' : $this->quantity_unit,
            'feature_image'       => url('images/product/feature/') . '/' . $this->product_image,
            'buying_price'        => $this->buying_price,
            'selling_price'       => $this->selling_price,
            'total_view'          => $this->total_view,
            'total_sold'          => $this->total_sold,
            'discount_type'       => $this->discount_type == 0 ? 1 : $this->discount_type,
            'discount_status'     => $this->discount_status,
            'discount'            => $this->discount,
            'discount_amount'     => $this->discount_amount,
            'product_description' => $this->product_description,
            'home_view'           => $this->home_view,
            'keyword'             => $this->product_keyword,
            'status'              => $this->status,
            'hot_deal'            => $this->hot_deal,
            'trialable'           => $this->trialable,
            'status_text'         => $this->status == 1 ? 'active' : 'inactive',
            'category'            => new CategoryResource($this->whenLoaded('category')),
            'sub_category'        => new SubCategoryResource($this->whenLoaded('sub_category')),
            'sub_sub_category'    => new SubSubCategoryResource($this->whenLoaded('sub_sub_category')),
            'brand'               => new BrandResource($this->whenLoaded('brand')),
            'product_size'        => $this->whenLoaded('size'),
            'product_color'       => $this->whenLoaded('color'),
            'product_keyword'     => $this->whenLoaded('productKeyword'),
            'multiple_image'      => ProductImageResource::collection($this->whenLoaded('multiple_image')),

        ];
    }
}
