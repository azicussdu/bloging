<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){

    }

    public function show(Category $category){
        $posts = $category->posts;
        $cats = Category::all();
        return view('posts.index', compact(['posts', 'cats']));
    }
}
