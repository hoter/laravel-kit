<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;

Route::view('/', 'welcome')->name('home');

Route::get('/posts', [PostController::class, 'index'])->name('posts.list');

Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware('permission:create-posts')->group(function () {
    Route::view('/posts/create', 'post.create');
    Route::post('/posts/create', [PostController::class, 'store'])->name('posts.store');
});

Route::middleware('permission:publish-posts')->group(function () {
    Route::get('/posts/{post}/publish', function (Post $post) {
        $post->update(['is_published' => true]);

        return redirect()->back()->with('status', 'Пост опубликован.');
    });
});

Route::middleware('role:admin')->prefix('admin')->group(function () {
    Route::view('/', 'post.index');
    Route::view('/posts', 'post.index')->name('posts.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::view('/posts/{post}/edit', 'post.edit');
    Route::view('/posts/{post}/delete', 'post.delete');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/posts', [PostController::class, 'store']);
});

Route::get('/reg', function() {
   return view('reg');
})->middleware('guest');

Route::post('/reg', [RegisterController::class, 'register'])->middleware('guest')->name('reg');

Route::get('/currency', [RegisterController::class, 'currency']);

Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

require __DIR__.'/settings.php';
