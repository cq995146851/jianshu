@extends('layout.main')
@section('title', '文章创建')
@section('content')
    <div class="col-sm-8 blog-main">
    <form action="{{route('posts.store')}}" method="POST">
        {{csrf_field()}}
        @include('layout._error')
        <div class="form-group">
            <label>标题</label>
            <input name="title" type="text" class="form-control" placeholder="这里是标题" value="{{old('title')}}">
        </div>
        <div class="form-group">
            <label>内容</label>
            <textarea id="content"  style="height:400px;max-height:500px;" name="content" class="form-control" placeholder="这里是内容" value="{{old('content')}}"></textarea>
        </div>
        <button type="submit" class="btn btn-default">提交</button>
    </form>
    </div>
@endsection
