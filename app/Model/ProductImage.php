<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    //

    protected $table = 'product_images';

    public function product()
    {
    
     return $this->belongsTo('App\Model\Product');

    }
}
