@extends('layouts.layout')

@section('title', 'Show post')
@section('content')
    <h2>{{ $post->title }}</h2>
    <p>{{ $post->content }}</p>
    <img src="{{asset('/storage/images/posts/'.$post->image)}}" alt="">

{{--    @foreach($comments as $comment)--}}
{{--        <h3>{{$comment->user->name}}</h3>--}}
{{--        <p>{{$comment->text}}</p>--}}
{{--    @endforeach--}}

    @if($meliked)
        <a href="{{route('likepost', $post->id)}}"><i class="fas fa-heart"> {{$likeNum}} </i>likes</a>
    @else
        <a href="{{route('likepost', $post->id)}}"><i class="far fa-heart"> {{$likeNum}} </i>likes</a>
    @endif


    <ol class="list-group">
        @foreach($post->comments as $comment)
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <div class="fw-bold">{{$comment->user->name}}</div>
                {{$comment->text}}
            </div>
            @can('delete', $comment)
                <form action="{{route('comments.destroy', $comment->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-close"></button>
                </form>
            @endcan
        </li>
        @endforeach
    </ol>

    <form action="{{route('comments.store')}}" method="post" class="mt-5">
        @csrf
        <textarea class="form-control" name="text" cols="30" rows="3"></textarea>
        <input type="hidden" name="post_id" value="{{$post->id}}">
        <button class="btn btn-primary mt-2">Add comment</button>
    </form>
@endsection
