<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Tool\M3Email;
use App\Tool\UUID;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    //注册页面
    public function toRegister()
    {
        return view('register.register');
    }

    //注册行为
    public function register(Request $request)
    {
        //验证
        $this->validate($request, [
            'name' => 'required|min:2|unique:users,name',
            'email' => 'required|email|unique:users,name',
            'password' => 'required|min:6|max:10|confirmed'
        ]);
        //逻辑
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->activation_token = UUID::create();
        $user->activated = false;
        $user->save();
        $this->sendEmailConFirm($user);
        //渲染
        session()->flash('success','验证邮件已发送到你的注册邮箱上，请注意查收');
        return redirect()->back();
    }

    private function sendEmailConFirm($user)
    {
        //发送邮件
        $to = $user->email;
        $subject = "感谢注册简书网站！请确认你的邮箱。";
        Mail::send('register.confirm_email',compact('user'), function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->first();
        $user->activated = true;
        $user->activation_token = '';
        $user->save();
        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功');
        return redirect()->route('posts.index');
    }
}
