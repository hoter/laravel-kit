<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can get posts', function () {
   $response = $this->getJson('/api/posts');

    $response->assertStatus(200)
        ->assertJsonStructure(['data']);
});

test('POST /api/posts creates post with valid token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/posts', [
            'title' => 'API Created Post Title',
            'content' => 'This is the content for the API-created post, long enough to pass validation.',
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => ['id', 'title', 'content'],
            'message',
        ])
        ->assertJsonFragment(['title' => 'API Created Post Title']);

    $this->assertDatabaseHas('posts', [
        'title' => 'API Created Post Title',
        'user_id' => $user->id,
    ]);
});

test('POST /api/posts returns 401 without token', function () {
    $response = $this->postJson('/api/posts', [
        'title' => 'Test Post',
        'content' => 'Some content that should be long enough for validation to pass through.',
    ]);

    $response->assertStatus(401);
});

test('POST /api/posts validates required fields', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/posts', [
            'title' => '',
            'content' => '',
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title', 'content']);
});
