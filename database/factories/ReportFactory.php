<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['spam', 'inappropriate', 'harassment', 'misinformation'];
        $statuses = ['pending', 'reviewed', 'action_taken', 'dismissed'];
        
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement($types),
            'reason' => fake()->paragraph(),
            'status' => fake()->randomElement($statuses),
        ];
    }
}