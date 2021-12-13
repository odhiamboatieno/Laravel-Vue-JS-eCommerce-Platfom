<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    // relation with category 

    public function category(){

    	return $this->belongsTo('App\Model\Category')->withDefault([
          'id' => 0,
          'category_name' => 'unknown' ,
          'category_native_name' => 'unknown' ,
    	]);
    }

    // relation with sub sub category 
    public function sub_sub_category()
    {
      return $this->hasMany('App\Model\SubSubCategory');
    }


    // relation with sub sub category 



    // relation with subcategory brand 

    // public function sub_category_brand(){

    // 	return $this->hasMany('App\Model\SubCategoryBrand');
    // }

      // relation with product

    public function product(){

      return $this->hasMany('App\Model\Product');
    } 


}
