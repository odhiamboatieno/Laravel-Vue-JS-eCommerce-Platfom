<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email','phone','location_id','avatar','password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // relation with order 

    public function order()
    {
        return $this->hasMany('App\Model\Order\Order');
    } 

    public function location()
    {
        return $this->belongsTo('App\Model\Setting\ShippingArea')->withDefault([
            'id' => 0,
            'city' => 'Not Found'
        ]);
    } 

    public function user_coupon()
    {
        return $this->hasOne('App\Model\Coupon\UserCoupon');
    }

    
}
