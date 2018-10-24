<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fan extends Model
{
    //获取粉丝用户
    public function fuser()
    {
        return $this->hasOne('App\Models\User', 'id', 'fan_id');
    }

    //获取关注用户
    public function suser()
    {
        return $this->hasOne('App\Models\User', 'id', 'star_id');
    }
}
