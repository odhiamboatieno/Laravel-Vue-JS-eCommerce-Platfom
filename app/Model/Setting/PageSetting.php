<?php

namespace App\Model\Setting;

use Illuminate\Database\Eloquent\Model;

class PageSetting extends Model
{
    protected $table = 'pages';
    protected $fillable = ['title','description'];
}
