<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public function category(){

    	return $this->belongsTo('App\Model\Category')->withDefault([
          'id'                   =>  0,
          'category_name'        =>  'unknown',
          'category_native_name' =>  'unknown',
          'icon'                 =>  'unknown',
    	]);
    }   

     public function sub_category(){

    	return $this->belongsTo('App\Model\SubCategory')->withDefault([
          'id'                       =>  0,
          'sub_category_name'        =>  'unknown',
          'sub_category_native_name' =>  'unknown',
          'icon'                     =>  'unknown',
    	]);
    }

     public function sub_sub_category(){

    	return $this->belongsTo('App\Model\SubSubCategory')->withDefault([
          'id'                           =>  0,
          'sub_sub_category_name'        =>  'N/A',
          'sub_sub_category_native_name' =>  'N/A',
          'icon'                         =>  'N/A',
    	]);
    }

    public function brand(){
      
       return $this->belongsTo('App\Model\Brand')->withDefault([
          'id'                =>  0,
          'brand_name'        =>  'N/A',
          'brand_native_name' =>  'no brand',
          'brand_logo'        =>  'unknown',
       ]);

    }

    public function productKeyword(){

    	return $this->hasMany('App\Model\ProductKeyword');
    }    

    public function multiple_image(){

    	return $this->hasMany('App\Model\ProductImage','product_id','id');
    }

    public function order_details(){

      return $this->hasMany('App\Model\Order\OrderDetails');
      
    }

//     public function size()
//     {
//           return $this->belongsTo('App\Model\Size');
//     }

//     public function color()
//     {
//           return $this->belongsTo('App\Model\Color');
//     }

    public function productSize(){

      return $this->hasMany('App\Model\ProductSize');

    } 

    public function color()
    {
        return $this->belongsToMany('App\Model\Color','product_color');
    } 
    public function size()
    {
        return $this->belongsToMany('App\Model\Size','product_size');
    } 
}
