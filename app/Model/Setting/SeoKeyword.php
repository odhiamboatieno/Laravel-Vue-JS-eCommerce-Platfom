<?php

namespace App\Model\Setting;

use Illuminate\Database\Eloquent\Model;

class SeoKeyword extends Model
{
    //

    public function seo_setting(){

    	return $this->belongsTo('App\Model\Setting\SeoSetting');
    }
}
