<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FoodProduct>
 */
class FoodProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            'Original Chocolate Cookies', 'Milk Chocolate Bar', 'Crunchy Peanut Butter',
            'Whole Grain Cereal', 'BBQ Potato Chips', 'Cola Soft Drink',
            'Strawberry Yogurt', 'Vanilla Ice Cream', 'Apple Juice',
            'Tomato Ketchup', 'Cheddar Cheese Slices', 'Sourdough Bread'
        ];

        $allergens = ['gluten', 'dairy', 'nuts', 'soy', 'eggs', 'shellfish', 'sesame'];
        $certifications = ['organic', 'non-gmo', 'fair-trade', 'kosher', 'halal', 'vegan', 'vegetarian'];
        $countries = ['United States', 'Canada', 'United Kingdom', 'Germany', 'France', 'Italy', 'Spain'];

        return [
            'brand_id' => Brand::factory(),
            'name' => fake()->randomElement($products),
            'description' => fake()->paragraph(),
            'ingredients' => fake()->words(random_int(5, 15), true),
            'nutrition_facts' => [
                'calories' => random_int(100, 500),
                'protein' => random_int(1, 20) . 'g',
                'carbohydrates' => random_int(10, 60) . 'g',
                'fat' => random_int(0, 30) . 'g',
                'sugar' => random_int(0, 25) . 'g',
                'sodium' => random_int(50, 1000) . 'mg',
            ],
            'allergens' => fake()->randomElements($allergens, random_int(0, 3)),
            'certifications' => fake()->randomElements($certifications, random_int(0, 2)),
            'manufacturing_location' => fake()->city() . ', USA',
            'country_of_origin' => fake()->randomElement($countries),
            'barcodes' => [fake()->ean13()],
            'image_url' => fake()->imageUrl(400, 300, 'food'),
            'average_rating' => fake()->randomFloat(2, 1, 5),
            'review_count' => random_int(0, 100),
        ];
    }
}