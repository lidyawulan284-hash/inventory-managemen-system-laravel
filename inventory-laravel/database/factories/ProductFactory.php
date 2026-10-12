<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'sku' => 'PRD-' . strtoupper(fake()->unique()->bothify('??###')),
            'name' => ucwords(fake()->words(3, true)),
            'description' => fake()->sentence(8),
            'price' => fake()->randomElement([25000, 50000, 75000, 120000, 250000, 499000, 1200000]),
            'stock' => fake()->numberBetween(10, 50),
            'minimum_stock' => 5,
        ];
    }

    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => fake()->numberBetween(0, 5),
            'minimum_stock' => 5,
        ]);
    }
}
