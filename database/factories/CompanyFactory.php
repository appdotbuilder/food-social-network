<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companies = [
            'Nestle', 'Unilever', 'PepsiCo', 'Coca-Cola Company', 'Kraft Heinz',
            'General Mills', 'Kellogg Company', 'Mars Inc', 'Mondelez International', 'Tyson Foods'
        ];

        $countries = ['United States', 'United Kingdom', 'Switzerland', 'Germany', 'France', 'Netherlands'];

        return [
            'name' => fake()->randomElement($companies),
            'description' => fake()->paragraph(),
            'country_of_origin' => fake()->randomElement($countries),
            'website' => fake()->url(),
            'logo_url' => fake()->imageUrl(200, 200, 'business'),
        ];
    }
}