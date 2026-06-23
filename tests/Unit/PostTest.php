<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('getShortContent returns first 100 characters', function () {
    $fullContent = fake()->text(200);

    $post = Post::factory()->for(User::factory(), 'author')->create([
        'content' => $fullContent,
    ]);

    expect($post->getShortContent())
        ->toBe(mb_substr($fullContent, 0, 100));
});

test('scopePublished returns only published posts', function () {
    $user = User::factory()->create();

    $published = Post::factory()->for($user, 'author')->create(['is_published' => true]);
    Post::factory()->for($user, 'author')->create(['is_published' => false]);

    $result = Post::published()->get();

    expect($result)->toHaveCount(1);
    expect($result->first()->id)->toBe($published->id);
});
