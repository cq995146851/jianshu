<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SendMessage;
use App\Models\Notice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoticeController extends Controller
{
    //列表
    public function index()
    {
        $notices = Notice::all();
        return view('admin.notice.index', compact('notices'));
    }

    //创建页面
    public function create()
    {
        return view('admin.notice.create');
    }

    //创建逻辑
    public function store(Request $request)
    {
        //验证
        $this->validate($request, [
           'title' => 'required|string',
            'content' => 'required|string'
        ]);
        //逻辑
        $notice = new Notice();
        $notice->title = $request->input('title');
        $notice->content = $request->input('content');
        $notice->save();
        dispatch(new SendMessage($notice));
        //渲染
        return redirect()->route('admin.notice.index');
    }
}
