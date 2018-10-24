<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    //通知页面
    public function index()
    {
        $notices = Auth::user()->notices()->get();
        return view('notice.index', compact('notices'));
    }
}
