<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use App\Models\FoodProduct;
use Inertia\Inertia;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with(['user.profile', 'foodProduct.brand'])
            ->visible()
            ->latest()
            ->paginate(10);

        return Inertia::render('reviews/index', [
            'reviews' => $reviews
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(FoodProduct $foodProduct)
    {
        $foodProduct->load('brand.company');

        return Inertia::render('reviews/create', [
            'foodProduct' => $foodProduct
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        $review = Review::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        // Update food product rating
        $this->updateFoodProductRating($review->food_product_id);

        return redirect()->route('food-products.show', $review->food_product_id)
            ->with('success', 'Review posted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        $review->load([
            'user.profile',
            'foodProduct.brand.company',
            'comments' => function ($query) {
                $query->visible()->with('user.profile')->latest();
            }
        ]);

        return Inertia::render('reviews/show', [
            'review' => $review
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        if (auth()->id() !== $review->user_id) {
            abort(403);
        }

        $review->load('foodProduct.brand.company');

        return Inertia::render('reviews/edit', [
            'review' => $review
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        if (auth()->id() !== $review->user_id) {
            abort(403);
        }

        $review->update($request->validated());

        // Update food product rating
        $this->updateFoodProductRating($review->food_product_id);

        return redirect()->route('reviews.show', $review)
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        if (auth()->id() !== $review->user_id) {
            abort(403);
        }

        $foodProductId = $review->food_product_id;
        $review->delete();

        // Update food product rating
        $this->updateFoodProductRating($foodProductId);

        return redirect()->route('food-products.show', $foodProductId)
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Update the average rating and review count for a food product.
     *
     * @param  int  $foodProductId
     * @return void
     */
    protected function updateFoodProductRating($foodProductId)
    {
        $foodProduct = FoodProduct::find($foodProductId);
        if ($foodProduct) {
            $reviews = Review::where('food_product_id', $foodProductId)->visible();
            $foodProduct->update([
                'average_rating' => $reviews->avg('rating') ?? 0,
                'review_count' => $reviews->count(),
            ]);
        }
    }
}