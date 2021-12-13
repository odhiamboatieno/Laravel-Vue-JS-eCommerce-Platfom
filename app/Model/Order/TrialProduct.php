<?php

namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;

class TrialProduct extends Model
{
    
    protected $with = ['color','size'];

    // relation with order
    
    public function order()
    {
    	return $this->belongsTo('App\Model\Order\Order');
    }
    
    // relation with product 
    public function product()
    {
    	return $this->belongsTo('App\Model\Product');
    }

    public function brand()
    {
        return $this->belongsTo('App\Model\Brand')->withDefault([
            'id' => 0,
            'brand_name' => 'unknown'
        ]);
    }

    public function category()
    {
        return $this->belongsTo('App\Model\Category')->withDefault([
            'id' => 0,
            'category_name' => 'unknown'
        ]);
    }

    public function sub_category()
    {
        return $this->belongsTo('App\Model\SubCategory')->withDefault([
            'id' => 0,
            'sub_category_name' => 'unknown'
        ]);
    }

    public function sub_sub_category()
    {
        return $this->belongsTo('App\Model\SubSubCategory')->withDefault([
          'id'                           =>  0,
          'sub_sub_category_name'        =>  'N/A',
          'sub_sub_category_native_name' =>  'N/A',
          // 'icon'                         =>  'N/A'
        ]);
    }

    public function size()
    {
        return $this->belongsTo('App\Model\Size');
    }


    public function color()
    {
        return $this->belongsTo('App\Model\Color');
    }
}
