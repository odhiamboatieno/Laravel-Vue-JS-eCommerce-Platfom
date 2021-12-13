<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = "campaigns";

    // relation with product 

    public function product()
    {
       return $this->hasMany('App\Model\Product');
    }
}
