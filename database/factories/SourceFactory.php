<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Source>
 */
class SourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['website', 'pdf', 'product_label', 'document'];
        
        return [
            'type' => fake()->randomElement($types),
            'title' => fake()->sentence(),
            'url' => fake()->url(),
            'description' => fake()->paragraph(),
            'verified_at' => fake()->boolean(70) ? fake()->dateTimeBetween('-1 year') : null,
        ];
    }
}