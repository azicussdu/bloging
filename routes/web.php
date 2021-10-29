<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\CategoryController;

Route::group(['middleware' => ['auth', 'setlang']], function(){
    Route::resource('posts', PostController::class)->except(['index', 'show']);
    Route::resource('comments', CommentController::class);

    Route::get('like/{post}', [PostController::class, 'likePost'])->name('likepost');

    Route::group([
        'middleware' => 'isAdmin',
        'prefix' => 'admin', //url: admin/posts
        'as' => 'admin.', //routename:  route('admin.posts.index')
    ], function(){
        Route::resource('posts', AdminPostController::class);
    });
});

Route::resource('posts', PostController::class)->only(['index', 'show'])->middleware('setlang');

Route::get('/', function(){
    return redirect()->route('posts.index');
});

Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

require __DIR__.'/auth.php';
