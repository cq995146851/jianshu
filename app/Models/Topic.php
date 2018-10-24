<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    //所有文章
    public function posts()
    {
        return $this->belongsToMany('App\Models\Post', 'post_topic', 'topic_id', 'post_id');
    }

    //文章数
    public function postTopics()
    {
        return $this->hasMany('App\Models\PostTopic', 'topic_id', 'id');
    }
}
