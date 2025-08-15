<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModerationLog>
 */
class ModerationLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actions = ['hide', 'delete', 'restore', 'dismiss_report'];
        
        return [
            'moderator_id' => User::factory(),
            'action' => fake()->randomElement($actions),
            'reason' => fake()->sentence(),
            'metadata' => ['ip_address' => fake()->ipv4()],
        ];
    }
}