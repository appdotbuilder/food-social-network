<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dietaryPreferences = ['vegetarian', 'vegan', 'gluten-free', 'keto', 'paleo', 'dairy-free'];

        return [
            'user_id' => User::factory(),
            'display_name' => fake()->name(),
            'bio' => fake()->sentence(),
            'avatar_url' => fake()->imageUrl(200, 200, 'people'),
            'location' => fake()->city() . ', USA',
            'dietary_preferences' => fake()->randomElements($dietaryPreferences, random_int(0, 2)),
            'is_private' => fake()->boolean(20), // 20% chance of being private
        ];
    }
}