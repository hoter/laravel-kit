<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('isApproved returns true when comment is approved', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user, 'author')->create();

    $comment = Comment::factory()->for($post)->for($user, 'author')->create([
        'is_approved' => true,
    ]);

    expect($comment->isApproved())->toBeTrue();
});

test('isApproved returns false when comment is not approved', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user, 'author')->create();

    $comment = Comment::factory()->for($post)->for($user, 'author')->create([
        'is_approved' => false,
    ]);

    expect($comment->isApproved())->toBeFalse();
});

test('belongsToPost returns post relationship', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user, 'author')->create();

    $comment = Comment::factory()->for($post)->for($user, 'author')->create();

    expect($comment->post)->toBeInstanceOf(Post::class);
    expect($comment->post->id)->toBe($post->id);
});
