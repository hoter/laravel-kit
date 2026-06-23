<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Tag;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('user has published posts', function () {
    $user = User::factory()->create();
    Post::factory()->for($user, 'author')->count(rand(1, 10))->create();

    expect($user->hasPublishedPosts())->toBe(true);
});

test('user is admin', function () {
   $user = User::factory()->create([
       'role' => 'admin',
   ]);

    expect($user->isAdmin())->toBe(true);
});

test('can get user full name', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
    ]);

    expect($user->name)->toBe('John Doe');
});

test('can create a user', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->name)->toBe('John Doe');
    expect($user->email)->toBe('john@example.com');
    $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
});

test('email must be unique', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    expect(fn () => User::factory()->create(['email' => 'existing@example.com']))
    ->toThrow('Integrity constraint violation');
});
