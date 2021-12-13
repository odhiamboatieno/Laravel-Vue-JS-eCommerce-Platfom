<?php

namespace App\Repository;

use App\AllStatic;
use App\Http\Resources\Product\ProductResource;
use App\Model\Product;

class ProductRepository
{

    // getFrontProduct()
    /*
     *@no_paginate = without pagination
     *@category_id = with category id
     *@brand_id = with brand id
     *only_take =  will take this amount
     */
    public function getFrontProduct($data)
    {
        $per_page = 8;
        if ($data['per_page']) {
            $per_page = $data['per_page'];
        }
        $search_keyword = $data['keyword'];
        $product        = Product::select('id', 'product_name', 'category_id', 'current_quantity',
            'product_image', 'selling_price', 'discount_type', 'discount_status',
            'discount', 'discount_amount', 'quantity_unit')->where('status', '=', AllStatic::$active)->orderBy('updated_at', 'desc');

        if ($data['category'] != '') {
            $product->where('category_id', '=', $data['category']);
        }
        if ($data['sub_category'] != '') {
            $product->where('sub_category_id', '=', $data['sub_category']);
        }
        if ($data['sub_sub_category'] != '') {
            $product->where('sub_sub_category_id', '=', $data['sub_sub_category']);
        }

        if ($data['brand'] != '') {
            $product->where('brand_id', '=', $data['brand']);
        }
        if ($data['campaign_id'] != '') {
            $product->where('campaign_id', '=', $data['campaign_id']);
        }
        if ($data['hot_deal'] != '') {
            $product->where('hot_deal', '=', $data['hot_deal']);
        }
        if ($data['discount_status'] != '') {
            $product->where('discount_status', '=', $data['discount_status']);
        }
        if ($data['without_id'] != '') {
            $product->where('id', '!=', $data['without_id']);
        }
        if ($search_keyword != '') {
            // this three field  or combination doing a and comibination with all other combination in upper
            $product->where(function ($query) use ($search_keyword) {
                $query->where('product_name', 'LIKE', '%' . $search_keyword . '%')
                    ->orWhere('product_native_name', 'LIKE', '%' . $search_keyword . '%')
                    ->orWhere('product_keyword', 'LIKE', '%' . $search_keyword . '%');
            });
        }
        if ($data['no_paginate']) {

            if ($data['take_only']) {
                $product->take($data['take_only']);
            }

            $product = $product->get();

        } else {
            $product = $product->paginate($per_page);
        }

        return ProductResource::collection($product);
    }

}
