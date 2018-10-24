<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use Searchable;

    //return 定义索引里面的type值
    public function searchableAs()
    {
        return 'post';
    }

    //定义有哪些字段需要搜索
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'content' => $this->content
        ];
    }

    protected $table = 'posts';

    //关联用户
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    //关联评论
    public function comments()
    {
        return $this->hasMany('App\Models\Comment')
                     ->orderBy('created_at', 'desc');
    }

    //和用户共同限制赞
    public function zan($user_id)
    {
        return $this->hasOne('App\Models\Zan')->where('user_id', $user_id);
    }

    //关联赞
    public function zans()
    {
        return $this->hasMany('App\Models\Zan');
    }

    //属于某个作者的文章
    public function scopeAuthorBy(Builder $query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    //所有专题
    public function postTopics()
    {
        return $this->hasMany('App\Models\PostTopic', 'post_id', 'id');
    }

    //不属于某个专题的文章
    public function scopeTopicNotBy(Builder $query, $topic_id)
    {
        return $query->doesntHave('postTopics', 'and', function ($q) use($topic_id) {
            $q->where('topic_id', $topic_id);
        });
    }

    //全局scope
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('avaliable', function (Builder $query) {
            $query->whereIn('status', [0, 1]);
        });
    }

}
