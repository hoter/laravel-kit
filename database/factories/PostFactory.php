<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends Factory<Post>
 */
//posts (user_id, title, slug, content, is_published, published_at)
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'slug' => fake()->slug(),
            'content' => fake()->text(100),
            'is_published' => fake()->boolean(70),
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory()
        ];
    }
}
