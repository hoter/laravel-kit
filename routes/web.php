<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;

Route::view('/', 'welcome')->name('home');

Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware('permission:create-posts')->group(function () {
    Route::view('/posts/create', 'post.create');
});

Route::middleware('permission:publish-posts')->group(function () {
    Route::get('/posts/{post}/publish', function (Post $post) {
        $post->update(['is_published' => true]);

        return redirect()->back()->with('status', 'Пост опубликован.');
    });
});

Route::middleware('role:admin')->prefix('admin')->group(function () {
    Route::view('/posts', 'post.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/posts/{post}', function (Post $post) {
        return view('post.view', ['post' => $post]);
    });
    Route::view('/posts/{post}/edit', 'post.edit');
    Route::view('/posts/{post}/delete', 'post.delete');
});

require __DIR__.'/settings.php';
