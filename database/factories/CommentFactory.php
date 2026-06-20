<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Post;

/**
 * @extends Factory<Comment>
 */
//comments (user_id, post_id, content, is_approved)
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->text(100),
            'is_approved' => fake()->boolean(80),
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(),
            'post_id' => Post::inRandomOrder()->value('id') ?? Post::factory(),
        ];
    }
}
