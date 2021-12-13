<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductKeyword extends Model
{
    //

    protected $table = 'product_keywords';

    public function product()
    {

    	return $this->belongsTo('App\Model\Product');
    }
}
