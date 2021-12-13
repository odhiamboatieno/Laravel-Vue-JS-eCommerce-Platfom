<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    // public function product()
    // {
    //     return $this->hasMany('App\Model\Product');
    // }

    public function product()
    {
        return $this->belongsToMany('App\Model\Product','products');
    }
}
