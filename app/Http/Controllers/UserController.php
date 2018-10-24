<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //设置页面
    public function toSetting(User $user)
    {
        $ret = $this->authorize('setting', $user);
        return view('user.setting', compact('user'));
    }

    //设置行为
    public function setting(User $user, Request $request)
    {
        //验证
        $this->validate($request, [
           'name' => 'required|min:2'
        ]);
        //逻辑
        $user->name = $request->input('name');
        if($request->file('avatar')) {
            $path = $request->file('avatar')->storePublicly(date('Y-m-d', time()));
            $user->avatar = asset('/storage/' . $path);
        }
        $user->save();
        //渲染
        return redirect()->back();
    }

    //个人中心
    public function show(User $user)
    {
        //发布文章信息，根据发布日期倒序取前十条
        $posts = $user->posts()->orderBy('created_at', 'desc')->take(10)->get();
        //粉丝信息
        $fans = $user->fans()->get();
        $fusers = User::whereIn('id', $fans->pluck('fan_id'))
            ->withCount(['posts', 'fans', 'stars'])
            ->get();
        //关注信息
        $stars = $user->stars()->get();
        $susers = User::whereIn('id', $stars->pluck('star_id'))
            ->withCount(['posts', 'fans', 'stars'])
            ->get();;
        //这个人信息,包含文章数,粉丝数，关注数
        $user = User::withCount(['posts', 'fans', 'stars'])->find($user->id);

        return view('user.show', compact('user', 'posts', 'fusers', 'susers'));
    }

    //关注
    public function fan(User $user)
    {
        $me = Auth::user();
        $me->doFan($user->id);
        return [
            'errno' => 0,
            'message' => '关注成功'
        ];
    }

    //取消关注
    public function unfan(User $user)
    {
        $me = Auth::user();
        $me->doUnfan($user->id);
        return [
            'errno' => 0,
            'message' => '取消关注成功'
        ];
    }
}
