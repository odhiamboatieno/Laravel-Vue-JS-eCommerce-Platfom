<?php

namespace App\Model\Setting;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    // relation with seo keyword 

    public function seo_keyword(){

    	return $this->hasMany('App\Model\Setting\SeoKeyword');
    }
}
