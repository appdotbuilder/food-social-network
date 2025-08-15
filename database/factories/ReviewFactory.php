<?php

namespace Database\Factories;

use App\Models\FoodProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reviews = [
            'This product exceeded my expectations! The taste is incredible and the quality is top-notch. I would definitely recommend it to anyone looking for a delicious snack.',
            'Not bad, but I\'ve had better. The flavor is okay but nothing special. Price point is reasonable for what you get.',
            'Absolutely love this! Been buying it for months now. Great ingredients and you can really taste the quality. Will continue to purchase.',
            'Disappointed with this purchase. The taste was bland and the texture was off. Wouldn\'t buy again.',
            'Pretty good overall. Nice packaging and decent taste. Could use a bit more flavor but it\'s a solid choice for a quick snack.',
            'Outstanding product! This has become a staple in our household. Kids love it and I feel good about giving it to them.',
            'Meh, it\'s okay. Nothing to write home about but it gets the job done. Probably won\'t repurchase.',
            'Really impressed with the quality! You can tell they use premium ingredients. Worth the extra cost.',
        ];

        $images = [];
        if (fake()->boolean(30)) { // 30% chance of having images
            $imageCount = random_int(1, 3);
            for ($i = 0; $i < $imageCount; $i++) {
                $images[] = fake()->imageUrl(400, 300, 'food');
            }
        }

        return [
            'user_id' => User::factory(),
            'food_product_id' => FoodProduct::factory(),
            'rating' => random_int(1, 5),
            'content' => fake()->randomElement($reviews),
            'images' => empty($images) ? null : $images,
            'likes_count' => random_int(0, 50),
            'comments_count' => random_int(0, 10),
        ];
    }
}