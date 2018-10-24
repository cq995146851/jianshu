<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //获取所有文章
    public function posts()
    {
        return $this->hasMany('App\Models\Post', 'user_id', 'id');
    }

    //关注我的
    public function fans()
    {
        return $this->hasMany('App\Models\Fan', 'star_id', 'id');
    }

    //我关注的
    public function stars()
    {
        return $this->hasMany('App\Models\Fan', 'fan_id', 'id');
    }

    //关注某人
    public function doFan($user_id)
    {
        $fan = new Fan();
        $fan->star_id = $user_id;
        return $this->stars()->save($fan);
    }

    //取消关注某人
    public function doUnfan($user_id)
    {
        $fan = new Fan();
        $fan->star_id = $user_id;
        $this->stars()->delete($fan);
    }

    //判断是否被某人关注
    public function hanFan($user_id)
    {
        return $this->fans()->where('fan_id', $user_id)->count();
    }

    //判断是否关注了某人
    public function hasStar($user_id)
    {
        return $this->stars()->where('star_id', $user_id)->count();
    }

    //用户收到的通知
    public function notices()
    {
        return $this->belongsToMany('App\Models\Notice', 'user_notice', 'user_id', 'notice_id')
            ->withPivot(['user_id', 'notice_id']);
    }

    //给用户增加通知
    public function addNotice($notice)
    {
        $this->notices()->save($notice);
    }
}
