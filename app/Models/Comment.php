<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //关联文章
    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }

    //关联用户
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
