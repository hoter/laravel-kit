<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'price' => fake()->randomFloat(2, 1, 1000),
            'stock' => fake()->numberBetween(0, 100),
            'description' => fake()->optional()->sentence(),
            'is_active' => fake()->boolean(80),
            'category_id' => Category::inRandomOrder()->value('id') ?? Category::factory(),
        ];
    }
}
