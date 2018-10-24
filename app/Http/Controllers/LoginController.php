<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //登录页面
    public function toLogin()
    {
        if(Auth::check()){
            session()->flash('danger', '您已经登录过了，请勿重新登录');
            return redirect()->route('posts.index');
        }
        return view('login.login');
    }

    //登录行为
    public function login(Request $request)
    {
        //验证
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6|max:10',
            'is_remember' => 'integer'
        ]);
        //逻辑
        if (Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ], boolval($request->input('is_remember')))) {
            if(Auth::user()->activated){
                session()->flash('success', '登录成功');
                return redirect()->route('posts.index');
            } else {
                Auth::logout();
                session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活');
                return redirect()->back();
            }

        } else {
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back();
        }
        //渲染
    }

    //登出
    public function logout()
    {
        Auth::logout();
        return redirect()->route('toLogin');
    }
}
