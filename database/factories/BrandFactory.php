<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = [
            'KitKat', 'Oreo', 'Cheerios', 'Pringles', 'Doritos', 'Lay\'s',
            'Coca-Cola', 'Pepsi', 'Sprite', 'Fanta', 'Mountain Dew', 'Dr Pepper',
            'Heinz', 'Kraft', 'Oscar Mayer', 'Philadelphia', 'Velveeta',
            'Kellogg\'s', 'Frosted Flakes', 'Special K', 'Rice Krispies',
            'M&M\'s', 'Snickers', 'Twix', 'Skittles', 'Starburst'
        ];

        return [
            'company_id' => Company::factory(),
            'name' => fake()->randomElement($brands),
            'description' => fake()->sentence(),
            'logo_url' => fake()->imageUrl(150, 150, 'food'),
        ];
    }
}