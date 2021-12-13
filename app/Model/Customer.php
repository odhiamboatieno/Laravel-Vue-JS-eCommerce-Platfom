<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customers";

    public function customerOrder()
    {
    	return $this->hasMany('App\Model\Order\Order','user_id');
    }
}
