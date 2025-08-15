<?php

namespace App\Http\Controllers;

use App\Models\FoodProduct;
use App\Models\Review;
use App\Models\Brand;
use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FoodNetworkController extends Controller
{
    /**
     * Display the main food network page.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $filters = $request->only([
            'brand',
            'company', 
            'country_of_origin',
            'allergens',
            'certifications',
            'min_rating',
            'max_rating'
        ]);

        // Build the query
        $query = FoodProduct::with(['brand.company', 'reviews'])
            ->visible()
            ->latest();

        // Apply search
        if ($search) {
            $query->search($search);
        }

        // Apply filters
        if (!empty($filters['brand'])) {
            $query->whereHas('brand', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['brand'] . '%');
            });
        }

        if (!empty($filters['company'])) {
            $query->whereHas('brand.company', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['company'] . '%');
            });
        }

        if (!empty($filters['country_of_origin'])) {
            $query->where('country_of_origin', $filters['country_of_origin']);
        }

        if (!empty($filters['allergens'])) {
            $allergens = is_array($filters['allergens']) ? $filters['allergens'] : [$filters['allergens']];
            $query->where(function ($q) use ($allergens) {
                foreach ($allergens as $allergen) {
                    $q->whereJsonContains('allergens', $allergen);
                }
            });
        }

        if (!empty($filters['certifications'])) {
            $certifications = is_array($filters['certifications']) ? $filters['certifications'] : [$filters['certifications']];
            $query->where(function ($q) use ($certifications) {
                foreach ($certifications as $cert) {
                    $q->whereJsonContains('certifications', $cert);
                }
            });
        }

        if (!empty($filters['min_rating'])) {
            $query->where('average_rating', '>=', $filters['min_rating']);
        }

        if (!empty($filters['max_rating'])) {
            $query->where('average_rating', '<=', $filters['max_rating']);
        }

        $foodProducts = $query->paginate(12)->withQueryString();

        // Get recent reviews for the feed
        $recentReviews = Review::with(['user.profile', 'foodProduct.brand'])
            ->visible()
            ->latest()
            ->limit(10)
            ->get();

        // Get filter options
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        $companies = Company::select('id', 'name')->orderBy('name')->get();
        $countries = FoodProduct::select('country_of_origin')
            ->whereNotNull('country_of_origin')
            ->distinct()
            ->orderBy('country_of_origin')
            ->pluck('country_of_origin');

        return Inertia::render('welcome', [
            'foodProducts' => $foodProducts,
            'recentReviews' => $recentReviews,
            'search' => $search,
            'filters' => $filters,
            'filterOptions' => [
                'brands' => $brands,
                'companies' => $companies,
                'countries' => $countries,
                'allergens' => ['gluten', 'dairy', 'nuts', 'soy', 'eggs', 'shellfish', 'sesame'],
                'certifications' => ['organic', 'non-gmo', 'fair-trade', 'kosher', 'halal', 'vegan', 'vegetarian'],
            ]
        ]);
    }
}