<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // relation with admin 

    public function admin()
    {
    	return $this->hasMany('App\Admin');
    }
}
