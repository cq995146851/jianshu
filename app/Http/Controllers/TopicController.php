<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostTopic;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    //专题页面
    public function show(Topic $topic)
    {
        $posts = $topic->posts()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        $myposts = Post::authorBy(Auth::id())->topicNotBy($topic->id)->get();
        $topic = Topic::withCount('postTopics')->find($topic->id);
        return view('topic.show', compact('posts', 'myposts', 'topic'));
    }

    //文章发布
    public function submit(Topic $topic, Request $request)
    {
        //验证
        $this->validate($request, [
           'post_ids' => 'required|array'
        ]);
        //逻辑
        $post_ids = $request->input('post_ids');
        foreach ($post_ids as $post_id) {
            $postTopic = new PostTopic();
            $postTopic->post_id = $post_id;
            $postTopic->topic_id = $topic->id;
            $postTopic->save();
        }
        //渲染
        session()->flash('success', '投稿成功');
        return redirect()->back();
    }
}
