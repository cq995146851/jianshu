@extends('layout.main')
@section('title', '文章编辑')
@section('content')
    <div class="col-sm-8 blog-main">
        <form action="{{route('posts.update', $post->id)}}" method="POST">
            {{csrf_field()}}
            {{method_field('PUT')}}
            @include('layout._error')
            <div class="form-group">
                <label>标题</label>
                <input name="title" type="text" class="form-control" placeholder="这里是标题" value="{{$post->title}}">
            </div>
            <div class="form-group">
                <label>内容</label>
                <textarea id="content" name="content" class="form-control" style="height:400px;max-height:500px;"  placeholder="这里是内容">
                    {{$post->content}}
                </textarea>
            </div>
            <button type="submit" class="btn btn-default">提交</button>
        </form>
        <br>
    </div>
@endsection


