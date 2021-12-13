<?php

namespace App\Model\Coupon;

use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    protected $table = 'user_coupon';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
