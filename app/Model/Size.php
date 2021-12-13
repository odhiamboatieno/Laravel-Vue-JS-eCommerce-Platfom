<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    public function category(){
        return $this->belongsTo('App\Model\Category')->withDefault([
            'id' => 0,
            'category_name' => 'N/A',
            'category_native_name' => 'N/A'
        ]);
    }

    public function product()
    {
        return $this->belongsToMany('App\Model\Product','products');
    }
}
