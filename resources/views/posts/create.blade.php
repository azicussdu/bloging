@extends('layouts.layout')

@section('title', 'user posts page')
@section('content')

        @if($errors->any())
            @foreach($errors->all() as $err)
                <div class="alert alert-danger">
                    {{$err}}
                </div>
            @endforeach
        @endif

        <form action="{{route('posts.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <label class="form-label">title</label>
            <input type="text" name="title" class="form-control">
            <label class="form-label">content</label>
            <textarea cols="30" rows="5" name="content" class="form-control"></textarea>

            <label class="form-label">category:</label>
            <select name="category_id" class="form-control">
                @foreach($cats as $cat)
                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                @endforeach
            </select>
            <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
            <div class="mb-3">
                <label for="formFile" class="form-label">Upload file</label>
                <input class="form-control" type="file" name="image" id="formFile">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add post</button>
        </form>

@endsection
