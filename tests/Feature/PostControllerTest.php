<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// index
test('index returns 200 and correct view', function () {
    Post::factory()->count(3)->create();

    $this->get('/posts')
        ->assertStatus(200)
        ->assertViewIs('post.index')
        ->assertViewHas('posts');
});

// show
test('show returns 200 for existing post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user, 'author')->create();

    $this->actingAs($user)
        ->get("/posts/{$post->id}")
        ->assertStatus(200)
        ->assertViewIs('post.view')
        ->assertSee($post->title);
});

test('show returns 404 for non-existing post', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/posts/99999')
        ->assertStatus(404);
});

// store
test('store creates post and redirects', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/posts', [
            'title' => 'My New Post Title Here',
            'content' => 'This is the post content that is long enough to pass the minimum validation requirements.',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('posts', [
        'title' => 'My New Post Title Here',
        'user_id' => $user->id,
    ]);
});

test('store validates title and content are required', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/posts', [
            'title' => '',
            'content' => '',
        ])
        ->assertSessionHasErrors(['title', 'content']);
});

test('store validates content minimum length', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/posts', [
            'title' => 'Valid Title',
            'content' => 'short',
        ])
        ->assertSessionHasErrors(['content']);
});

// update
test('update modifies post data', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user, 'author')->create([
        'title' => 'Original Title',
        'content' => 'Original content that is long enough for validation rules to pass.',
    ]);

    $this->actingAs($user)
        ->put("/posts/{$post->id}", [
            'title' => 'Updated Title Here',
            'content' => 'Updated content that is long enough to pass the validation rules for this post.',
        ])
        ->assertRedirect(route('posts.show', $post));

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Updated Title Here',
    ]);
});

// destroy
test('destroy deletes post from database', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user, 'author')->create();

    $this->actingAs($user)
        ->delete("/posts/{$post->id}")
        ->assertRedirect(route('posts.list'));

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});
