<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::view('/posts/create', 'post.create');
    Route::view('/posts/{post}/edit', 'post.edit');
    Route::view('/posts/{post}/delete', 'post.delete');
    Route::get('/posts/{post}', function(Post $post) {
        return view('post.view', ['post' => $post]);
    });
    Route::view('/admin/posts', 'post.index');
});

require __DIR__.'/settings.php';
