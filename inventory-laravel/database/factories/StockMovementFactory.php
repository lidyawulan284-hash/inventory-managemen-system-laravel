<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockMovement>
 */
class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['IN', 'OUT']),
            'quantity' => fake()->numberBetween(1, 15),
            'reference_number' => 'MOV-' . strtoupper(fake()->bothify('###??')),
            'notes' => fake()->sentence(5),
        ];
    }
}
