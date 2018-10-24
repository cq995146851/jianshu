@extends('layout.main')
@section('title', '文章详情')
@section('content')
    <div class="col-sm-8 blog-main">
        <div class="blog-post">
            <div style="display:inline-flex">
                <h2 class="blog-post-title">{{$post->title}}</h2>
                @can('update', $post)
                <a style="margin: auto"  href="{{route('posts.edit', $post->id)}}">
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                </a>
                @endcan
                @can('delete', $post)
                <form action="{{route('posts.destroy', $post->id)}}" method="post" id="myForm">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}
                    <a style="margin: auto"  href="javascript:void(0)" onclick="deletePost()">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    </a>
                </form>
                @endcan
            </div>

            <p class="blog-post-meta">{{$post->created_at->toFormattedDateString()}} by <a href="#">{{$post->user->name}}</a></p>

            <p>{!! $post->content !!}</p>
            <div>
                @if($post->zan(\Auth::id())->exists())
                <a href="{{route('posts.unzan', $post->id)}}" type="button" class="btn btn-danger btn-lg">取消赞</a>
                @else
                <a href="{{route('posts.zan', $post->id)}}" type="button" class="btn btn-primary btn-lg">赞</a>
                @endif
            </div>
        </div>

        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">评论</div>

            <!-- List group -->
            <ul class="list-group">
                @foreach($post->comments as $comment)
                    <li class="list-group-item">
                        <h5>{{$comment->created_at}} by {{$comment->user->name}}</h5>
                        <div>
                            {{$comment->content}}
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">发表评论</div>

            <!-- List group -->
            <ul class="list-group">
                @include('layout._error')
                <form action="{{route('posts.comment', $post->id)}}" method="post">
                    {{csrf_field()}}
                    <li class="list-group-item">
                        <textarea name="content" class="form-control" rows="10"></textarea>
                        <button class="btn btn-default" type="submit">提交</button>
                    </li>
                </form>
            </ul>
        </div>
    </div>
@endsection
@section('my-js')
    <script>
        function deletePost () {
            $("#myForm").submit();
        }
    </script>
@endsection



