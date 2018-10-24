<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //登录页面
    public function index()
    {
        return view('admin.login.login');
    }

    //登录逻辑
    public function login(Request $request)
    {
        //验证
        $this->validate($request, [
           'name' => 'required|min:2',
           'password' => 'required|min:6'
        ]);
        //逻辑
        if(Auth::guard('admin')->attempt([
            'name' => $request->input('name'),
            'password' => $request->input('password')
        ])){
            return redirect()->route('admin.home.index');
        } else {
            return redirect()->back()->withErrors('用户名密码不匹配');
        }
    }

    //登出
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
