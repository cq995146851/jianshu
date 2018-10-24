<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Zan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    //文章列表页
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')
                ->withCount(['comments', 'zans'])
                ->with('user') //使用预加载进行优化
                ->paginate(5);
        return view('post.index', compact('posts'));
    }

    //文章详情页
    public function show(Post $post)
    {
        //先加载评论，不让业务逻辑在视图层
        $post->load('comments');
        return view('post.show', compact('post'));
    }

    //创建文章
    public function create()
    {
        return view('post.create');
    }

    public function store(Request $request)
    {
        $post = new Post();
        $this->validate($request, [
           'title' => 'required|string|max:100|min:5',
           'content' => 'required|string|min:10'
        ]);
        $post->user_id = Auth::id();
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();
        session()->flash('success','文章添加成功');
        return redirect()->route('posts.index');
    }

    //编辑文章
    public function edit(Post $post)
    {
        return view('post.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
          $this->validate($request, [
              'title' => 'required|string|max:100|min:5',
              'content' => 'required|string|min:10'
          ]);
          $this->authorize('update', $post);
          $post->title = $request->input('title');
          $post->content = $request->input('title');
          $post->save();
          session()->flash('success','文章修改成功');
          return redirect()->route('posts.show', [$post]);
    }

    //删除文章
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        session()->flash('success','文章删除成功');
        return redirect()->route('posts.index');
    }

    //图片上传
    public function imageUpload(Request $request)
    {
        //需要先在config目录下的filessystems.php将配置文件修改一下
        //然后php artisan storage:link 将public 下的storage映射到storage目录下的public
        $path = $request->file('wangEditorH5File')->storePublicly(date('Y-m-d', time()));
        return asset('/storage/' . $path);
    }

    //提交评论
    public function comment(Post $post, Request $request)
    {
        //验证
        $this->validate($request, [
           'content' => 'required|min:3'
        ]);
        //逻辑
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->post_id = $post->id;
        $comment->content = $request->input('content');
        $comment->save();
        //渲染
        session()->flash('success', '评论成功');
        return redirect()->back();
    }

    //赞逻辑
    public function zan(Post $post)
    {
        $param = [
          'user_id' => Auth::id(),
          'post_id' => $post->id
        ];
        //数据库中先查找，没有则创建
        Zan::firstOrCreate($param);
        session()->flash('success', '成功赞了这篇文章');
        return redirect()->back();
    }

    //取消赞逻辑
    public function unzan(Post $post)
    {
        $post->zan(Auth::id())->delete();
        session()->flash('success', '成功取消');
        return redirect()->back();
    }

    //搜索页
    public function search(Request $request)
    {
        //验证
        $this->validate($request, [
            'query' => 'required'
        ]);
        //逻辑
        $query = $request->input('query');
        $posts = Post::search($query)
            ->paginate(1);
        //渲染
        return view('post.search', compact('posts', 'query'));
    }
}
