<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Company;
use App\Models\FoodProduct;
use App\Models\Review;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class FoodNetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create companies first
        $companies = [
            [
                'name' => 'Nestle',
                'description' => 'Swiss multinational food and drink processing conglomerate',
                'country_of_origin' => 'Switzerland',
                'website' => 'https://www.nestle.com',
                'logo_url' => 'https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?w=200&h=200&fit=crop',
            ],
            [
                'name' => 'Unilever',
                'description' => 'British multinational consumer goods company',
                'country_of_origin' => 'United Kingdom',
                'website' => 'https://www.unilever.com',
                'logo_url' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=200&h=200&fit=crop',
            ],
            [
                'name' => 'PepsiCo',
                'description' => 'American multinational food and beverage corporation',
                'country_of_origin' => 'United States',
                'website' => 'https://www.pepsico.com',
                'logo_url' => 'https://images.unsplash.com/photo-1594971475674-6a97f8e13f9f?w=200&h=200&fit=crop',
            ],
        ];

        foreach ($companies as $companyData) {
            $company = Company::create($companyData);

            // Create brands for each company
            $brandNames = match($company->name) {
                'Nestle' => ['KitKat', 'Smarties', 'Aero', 'Butterfinger'],
                'Unilever' => ['Ben & Jerry\'s', 'Magnum', 'Cornetto', 'Breyers'],
                'PepsiCo' => ['Lay\'s', 'Doritos', 'Cheetos', 'Tostitos'],
                default => ['Brand A', 'Brand B']
            };

            foreach ($brandNames as $brandName) {
                $brand = Brand::create([
                    'company_id' => $company->id,
                    'name' => $brandName,
                    'description' => "Premium {$brandName} products from {$company->name}",
                    'logo_url' => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=150&h=150&fit=crop',
                ]);

                // Create food products for each brand
                $productCount = random_int(3, 6);
                for ($i = 0; $i < $productCount; $i++) {
                    $product = FoodProduct::create([
                        'brand_id' => $brand->id,
                        'name' => match($brandName) {
                            'KitKat' => ['Original Milk Chocolate', 'Dark Chocolate', 'White Chocolate', 'Peanut Butter'][random_int(0, 3)] . ' KitKat',
                            'Lay\'s' => ['Classic', 'BBQ', 'Sour Cream & Onion', 'Salt & Vinegar'][random_int(0, 3)] . ' Lay\'s Chips',
                            'Ben & Jerry\'s' => ['Chocolate Fudge Brownie', 'Cookie Dough', 'Strawberry Cheesecake', 'Mint Chocolate'][random_int(0, 3)] . ' Ice Cream',
                            default => fake()->words(2, true) . ' ' . $brandName,
                        },
                        'description' => fake()->paragraph(),
                        'ingredients' => fake()->words(random_int(8, 15), true),
                        'nutrition_facts' => [
                            'calories' => random_int(150, 400),
                            'protein' => random_int(2, 15) . 'g',
                            'carbohydrates' => random_int(20, 45) . 'g',
                            'fat' => random_int(5, 25) . 'g',
                            'sugar' => random_int(5, 20) . 'g',
                            'sodium' => random_int(100, 800) . 'mg',
                        ],
                        'allergens' => fake()->randomElements(['gluten', 'dairy', 'nuts', 'soy', 'eggs'], random_int(1, 3)),
                        'certifications' => fake()->randomElements(['organic', 'non-gmo', 'kosher', 'halal'], random_int(0, 2)),
                        'manufacturing_location' => fake()->city() . ', ' . $company->country_of_origin,
                        'country_of_origin' => $company->country_of_origin,
                        'barcodes' => [fake()->ean13()],
                        'image_url' => 'https://images.unsplash.com/photo-1599599810694-57a2ca8276a8?w=400&h=300&fit=crop',
                        'average_rating' => fake()->randomFloat(1, 2.5, 5.0),
                        'review_count' => random_int(5, 50),
                    ]);
                }
            }
        }

        // Create some users with profiles
        $users = User::factory(20)->create();
        foreach ($users as $user) {
            UserProfile::factory()->create(['user_id' => $user->id]);
        }

        // Create reviews for random products
        $products = FoodProduct::all();
        foreach ($products as $product) {
            $reviewCount = random_int(3, 8);
            for ($i = 0; $i < $reviewCount; $i++) {
                Review::factory()->create([
                    'food_product_id' => $product->id,
                    'user_id' => $users->random()->id,
                ]);
            }

            // Update the product's review stats
            $reviews = Review::where('food_product_id', $product->id);
            $product->update([
                'average_rating' => $reviews->avg('rating') ?? 0,
                'review_count' => $reviews->count(),
            ]);
        }
    }
}