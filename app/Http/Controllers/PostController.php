<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class PostController extends Controller
{

    public function likePost(Post $post){
        $like = Like::where('post_id', $post->id)->where('user_id', auth()->user()->id)->first();

        if($like){
            $like->delete();
        }
        else{
            Like::create([
                'post_id' => $post->id,
                'user_id' => auth()->user()->id,
            ]);
        }

        return redirect()->back();
    }

    public function index()
    {
        $posts = Post::with('category')->get();
        $cats = Category::all();
        return view('posts.index')->with(['posts'=> $posts, 'cats'=>$cats]);
    }

    public function create()
    {
        $cats = Category::all();
        return view('posts.create', compact('cats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'=> ['required', 'max:255'],
            'content'=>['required'],
            'category_id'=>['required', 'integer', 'exists:categories,id'],
            'user_id'=>['required', 'integer', 'exists:users,id'],
            'image'=>'mimes:jpg,bmp,png',
        ]);

        $imageName = "default.jpg";
        if($request->hasFile('image')){
            $imageName = $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/images/posts', $imageName);
        }

        Post::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'category_id' => $request->input('category_id'),
            'user_id' => $request->input('user_id'),
            'image' => $imageName,
        ]);

        return redirect()->route('posts.index')->with('message', 'You created successfully');
    }

    public function show(Post $post)
    {
        $cats = Category::all();
        $likeNum = Like::where('post_id', $post->id)->count();
        $meliked = Like::where('post_id', $post->id)->where('user_id', auth()->user()->id)->first();
        return view('posts.show', compact( 'post', 'cats', 'likeNum', 'meliked'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        $cats = Category::all();
        return view('posts.edit')->with(['post' => $post, 'cats' => $cats]);
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title'=> ['required', 'max:255'],
            'content'=>['required'],
            'category_id'=>['required', 'integer', 'exists:categories,id'],
        ]);

        $post->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'category_id' => $request->input('category_id'),
        ]);

        return redirect()->route('posts.index')->with('message', 'You updated successfully');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();
        return redirect()->route('posts.index')->with('message', 'You deleted successfully');
    }
}
