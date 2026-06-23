<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Events\CommentAdded;

uses(RefreshDatabase::class);

test('can send a notification after creating a new comment', function () {
   Event::fake();

    $user = User::factory()->create();
    $post = Post::factory()->for($user, 'author')->create();

    $this->actingAs($user)
        ->post("/posts/{$post->id}/comments", [
        'content' => 'This content is long enough to pass the minimum validation length of fifty characters.',
    ]);

    Event::assertDispatched(CommentAdded::class);
});

test('authenticated user can create comment', function () {
    $user = User::factory()->create();
    $author = User::factory()->create();
    $post = Post::factory()->for($author, 'author')->create();

    $this->actingAs($user)
        ->post("/posts/{$post->id}/comments", [
            'content' => 'This is a test comment.',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'user_id' => $user->id,
        'content' => 'This is a test comment.',
    ]);
});

test('guest cannot create comment', function () {
    $author = User::factory()->create();
    $post = Post::factory()->for($author, 'author')->create();

    $this->post("/posts/{$post->id}/comments", [
        'content' => 'Test comment.',
    ])
        ->assertRedirect('/login');
});

test('author can delete own comment', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user, 'author')->create();
    $comment = Comment::factory()->for($post)->for($user, 'author')->create();

    $this->actingAs($user)
        ->delete("/comments/{$comment->id}")
        ->assertRedirect();

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

test('non-author cannot delete comment', function () {
    $author = User::factory()->create();
    $otherUser = User::factory()->create();
    $post = Post::factory()->for($author, 'author')->create();
    $comment = Comment::factory()->for($post)->for($author, 'author')->create();

    $this->actingAs($otherUser)
        ->delete("/comments/{$comment->id}")
        ->assertStatus(403);

    $this->assertDatabaseHas('comments', ['id' => $comment->id]);
});
