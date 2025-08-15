<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFoodProductRequest;
use App\Http\Requests\UpdateFoodProductRequest;
use App\Models\FoodProduct;
use App\Models\Brand;
use Inertia\Inertia;

class FoodProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foodProducts = FoodProduct::with(['brand.company', 'reviews'])
            ->visible()
            ->latest()
            ->paginate(12);

        return Inertia::render('food-products/index', [
            'foodProducts' => $foodProducts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::with('company')->orderBy('name')->get();

        return Inertia::render('food-products/create', [
            'brands' => $brands
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFoodProductRequest $request)
    {
        $foodProduct = FoodProduct::create($request->validated());

        return redirect()->route('food-products.show', $foodProduct)
            ->with('success', 'Food product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodProduct $foodProduct)
    {
        $foodProduct->load([
            'brand.company',
            'reviews' => function ($query) {
                $query->visible()
                    ->with(['user.profile', 'comments' => function ($q) {
                        $q->visible()->with('user.profile');
                    }])
                    ->latest();
            },
            'sources'
        ]);

        return Inertia::render('food-products/show', [
            'foodProduct' => $foodProduct
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodProduct $foodProduct)
    {
        $brands = Brand::with('company')->orderBy('name')->get();

        return Inertia::render('food-products/edit', [
            'foodProduct' => $foodProduct,
            'brands' => $brands
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodProductRequest $request, FoodProduct $foodProduct)
    {
        $foodProduct->update($request->validated());

        return redirect()->route('food-products.show', $foodProduct)
            ->with('success', 'Food product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodProduct $foodProduct)
    {
        $foodProduct->delete();

        return redirect()->route('food-products.index')
            ->with('success', 'Food product deleted successfully.');
    }
}