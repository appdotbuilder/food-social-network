<?php

namespace Database\Factories;

use App\Models\FoodList;
use App\Models\FoodProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FoodListItem>
 */
class FoodListItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'food_list_id' => FoodList::factory(),
            'food_product_id' => FoodProduct::factory(),
            'note' => fake()->sentence(),
            'order' => random_int(1, 100),
        ];
    }
}