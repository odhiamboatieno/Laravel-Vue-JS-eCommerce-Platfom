<?php

namespace App\Model\Setting;

use Illuminate\Database\Eloquent\Model;

class Messenger extends Model
{
    protected $table = "messengers";
    protected $fillable = ['app_id'];
}
