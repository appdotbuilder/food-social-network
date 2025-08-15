import React, { useState } from 'react';
import { Head, router, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface FoodProduct {
    id: number;
    name: string;
    description: string | null;
    image_url: string | null;
    average_rating: number;
    review_count: number;
    brand: {
        id: number;
        name: string;
        company: {
            id: number;
            name: string;
        };
    };
    allergens: string[] | null;
    certifications: string[] | null;
    country_of_origin: string | null;
}

interface Review {
    id: number;
    rating: number;
    content: string;
    created_at: string;
    user: {
        id: number;
        name: string;
        profile: {
            display_name: string | null;
            avatar_url: string | null;
        } | null;
    };
    food_product: {
        id: number;
        name: string;
        brand: {
            name: string;
        };
    };
}

interface FilterOptions {
    brands: Array<{ id: number; name: string }>;
    companies: Array<{ id: number; name: string }>;
    countries: string[];
    allergens: string[];
    certifications: string[];
}

interface Props {
    foodProducts: {
        data: FoodProduct[];
        links: Array<{ url: string | null; label: string; active: boolean }>;
        meta: { total: number; per_page: number; current_page: number };
    };
    recentReviews: Review[];
    search: string | null;
    filters: Record<string, string | string[]>;
    filterOptions: FilterOptions;
    [key: string]: unknown;
}

export default function Welcome({ foodProducts, recentReviews, search, filters, filterOptions }: Props) {
    const [showFilters, setShowFilters] = useState(false);
    const [searchQuery, setSearchQuery] = useState(search || '');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/', { search: searchQuery }, {
            preserveState: true,
            preserveScroll: true
        });
    };

    const updateFilter = (key: string, value: string) => {
        const newFilters = { ...filters };
        if (value === '' || value === null) {
            delete newFilters[key];
        } else {
            newFilters[key] = value;
        }
        
        router.get('/', { search, ...newFilters }, {
            preserveState: true,
            preserveScroll: true
        });
    };

    const renderStars = (rating: number) => {
        return Array.from({ length: 5 }, (_, i) => (
            <span key={i} className={i < Math.floor(rating) ? 'text-yellow-400' : 'text-gray-300'}>
                ⭐
            </span>
        ));
    };

    return (
        <AppShell>
            <Head title="🍽️ Food Social Network - Discover & Review Food Products" />
            
            {/* Hero Section */}
            <div className="bg-gradient-to-br from-orange-50 to-red-50 py-16">
                <div className="container mx-auto px-4">
                    <div className="text-center max-w-4xl mx-auto">
                        <h1 className="text-5xl font-bold text-gray-900 mb-6">
                            🍽️ Food Social Network
                        </h1>
                        <p className="text-xl text-gray-600 mb-8">
                            Discover, review, and share amazing food products with a community of food enthusiasts. 
                            Find detailed information about ingredients, nutrition, and authentic reviews.
                        </p>
                        
                        {/* Key Features */}
                        <div className="grid md:grid-cols-3 gap-6 mb-12">
                            <div className="bg-white p-6 rounded-xl shadow-sm">
                                <div className="text-3xl mb-3">🔍</div>
                                <h3 className="font-semibold text-lg mb-2">Discover Products</h3>
                                <p className="text-gray-600 text-sm">Search thousands of food products with detailed information and verified sources</p>
                            </div>
                            <div className="bg-white p-6 rounded-xl shadow-sm">
                                <div className="text-3xl mb-3">⭐</div>
                                <h3 className="font-semibold text-lg mb-2">Share Reviews</h3>
                                <p className="text-gray-600 text-sm">Write detailed reviews with photos and help others make informed choices</p>
                            </div>
                            <div className="bg-white p-6 rounded-xl shadow-sm">
                                <div className="text-3xl mb-3">👥</div>
                                <h3 className="font-semibold text-lg mb-2">Join Community</h3>
                                <p className="text-gray-600 text-sm">Follow food enthusiasts and discover curated lists of amazing products</p>
                            </div>
                        </div>

                        {/* CTA Buttons */}
                        <div className="flex gap-4 justify-center">
                            <Link 
                                href="/register"
                                className="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors"
                            >
                                Join Community
                            </Link>
                            <Link 
                                href="/login"
                                className="bg-white hover:bg-gray-50 text-orange-600 px-8 py-3 rounded-lg font-semibold border-2 border-orange-600 transition-colors"
                            >
                                Sign In
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div className="container mx-auto px-4 py-8">
                <div className="flex flex-col lg:flex-row gap-8">
                    {/* Sidebar - Filters */}
                    <div className="lg:w-80">
                        <div className="bg-white rounded-xl shadow-sm p-6 sticky top-4">
                            <div className="flex items-center justify-between mb-6">
                                <h2 className="text-lg font-semibold">🔍 Search & Filter</h2>
                                <button
                                    onClick={() => setShowFilters(!showFilters)}
                                    className="lg:hidden text-gray-500 hover:text-gray-700"
                                >
                                    {showFilters ? '✕' : '☰'}
                                </button>
                            </div>

                            <div className={`space-y-6 ${showFilters ? 'block' : 'hidden lg:block'}`}>
                                {/* Search */}
                                <form onSubmit={handleSearch}>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Search Products
                                    </label>
                                    <div className="flex">
                                        <input
                                            type="text"
                                            value={searchQuery}
                                            onChange={(e) => setSearchQuery(e.target.value)}
                                            placeholder="Search by name, ingredients..."
                                            className="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                        />
                                        <button
                                            type="submit"
                                            className="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-r-lg transition-colors"
                                        >
                                            🔍
                                        </button>
                                    </div>
                                </form>

                                {/* Brand Filter */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Brand
                                    </label>
                                    <select
                                        value={filters.brand || ''}
                                        onChange={(e) => updateFilter('brand', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    >
                                        <option value="">All Brands</option>
                                        {filterOptions.brands.map(brand => (
                                            <option key={brand.id} value={brand.name}>{brand.name}</option>
                                        ))}
                                    </select>
                                </div>

                                {/* Country Filter */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Country of Origin
                                    </label>
                                    <select
                                        value={filters.country_of_origin || ''}
                                        onChange={(e) => updateFilter('country_of_origin', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    >
                                        <option value="">All Countries</option>
                                        {filterOptions.countries.map(country => (
                                            <option key={country} value={country}>{country}</option>
                                        ))}
                                    </select>
                                </div>

                                {/* Rating Filter */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Minimum Rating
                                    </label>
                                    <select
                                        value={filters.min_rating || ''}
                                        onChange={(e) => updateFilter('min_rating', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    >
                                        <option value="">Any Rating</option>
                                        <option value="4">4+ Stars</option>
                                        <option value="3">3+ Stars</option>
                                        <option value="2">2+ Stars</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Main Content */}
                    <div className="flex-1">
                        {/* Results Header */}
                        <div className="flex items-center justify-between mb-6">
                            <h2 className="text-2xl font-bold text-gray-900">
                                🍽️ Food Products {search && `- "${search}"`}
                            </h2>
                            <p className="text-gray-600">
                                {foodProducts.meta.total} products found
                            </p>
                        </div>

                        {/* Food Products Grid */}
                        <div className="grid md:grid-cols-2 xl:grid-cols-3 gap-6 mb-12">
                            {foodProducts.data.map((product) => (
                                <div key={product.id} className="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                                    {/* Product Image */}
                                    <div className="h-48 bg-gray-100 relative">
                                        {product.image_url ? (
                                            <img
                                                src={product.image_url}
                                                alt={product.name}
                                                className="w-full h-full object-cover"
                                            />
                                        ) : (
                                            <div className="w-full h-full flex items-center justify-center text-4xl">
                                                🍽️
                                            </div>
                                        )}
                                        {product.certifications && product.certifications.length > 0 && (
                                            <div className="absolute top-2 right-2">
                                                <span className="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                                    {product.certifications[0]}
                                                </span>
                                            </div>
                                        )}
                                    </div>

                                    {/* Product Info */}
                                    <div className="p-4">
                                        <div className="mb-2">
                                            <p className="text-sm text-gray-500">{product.brand.company.name} • {product.brand.name}</p>
                                            <h3 className="font-semibold text-lg text-gray-900 mb-1">{product.name}</h3>
                                        </div>

                                        {product.description && (
                                            <p className="text-gray-600 text-sm mb-3 line-clamp-2">
                                                {product.description}
                                            </p>
                                        )}

                                        {/* Rating */}
                                        <div className="flex items-center gap-2 mb-3">
                                            <div className="flex">
                                                {renderStars(product.average_rating)}
                                            </div>
                                            <span className="text-sm text-gray-600">
                                                {product.average_rating.toFixed(1)} ({product.review_count} reviews)
                                            </span>
                                        </div>

                                        {/* Allergens */}
                                        {product.allergens && product.allergens.length > 0 && (
                                            <div className="mb-3">
                                                <p className="text-xs text-gray-500 mb-1">Contains:</p>
                                                <div className="flex flex-wrap gap-1">
                                                    {product.allergens.slice(0, 3).map((allergen) => (
                                                        <span key={allergen} className="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">
                                                            {allergen}
                                                        </span>
                                                    ))}
                                                    {product.allergens.length > 3 && (
                                                        <span className="text-xs text-gray-500">
                                                            +{product.allergens.length - 3} more
                                                        </span>
                                                    )}
                                                </div>
                                            </div>
                                        )}

                                        <Link
                                            href={`/food-products/${product.id}`}
                                            className="inline-block bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                        >
                                            View Details
                                        </Link>
                                    </div>
                                </div>
                            ))}
                        </div>

                        {/* Recent Reviews Section */}
                        {recentReviews.length > 0 && (
                            <div className="bg-white rounded-xl shadow-sm p-6">
                                <h2 className="text-xl font-bold text-gray-900 mb-6">💬 Recent Reviews</h2>
                                <div className="space-y-4">
                                    {recentReviews.slice(0, 5).map((review) => (
                                        <div key={review.id} className="border-b border-gray-200 last:border-b-0 pb-4 last:pb-0">
                                            <div className="flex items-start gap-3">
                                                <div className="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                    {review.user.profile?.avatar_url ? (
                                                        <img
                                                            src={review.user.profile.avatar_url}
                                                            alt={review.user.name}
                                                            className="w-10 h-10 rounded-full object-cover"
                                                        />
                                                    ) : (
                                                        <span className="text-orange-600 font-semibold">
                                                            {review.user.name.charAt(0).toUpperCase()}
                                                        </span>
                                                    )}
                                                </div>
                                                <div className="flex-1">
                                                    <div className="flex items-center gap-2 mb-1">
                                                        <span className="font-semibold text-sm">
                                                            {review.user.profile?.display_name || review.user.name}
                                                        </span>
                                                        <div className="flex">
                                                            {renderStars(review.rating)}
                                                        </div>
                                                        <span className="text-xs text-gray-500">
                                                            {new Date(review.created_at).toLocaleDateString()}
                                                        </span>
                                                    </div>
                                                    <p className="text-sm text-gray-600 mb-1">
                                                        <Link 
                                                            href={`/food-products/${review.food_product.id}`}
                                                            className="font-medium text-orange-600 hover:underline"
                                                        >
                                                            {review.food_product.name}
                                                        </Link>
                                                        <span className="text-gray-400"> by {review.food_product.brand.name}</span>
                                                    </p>
                                                    <p className="text-sm text-gray-700 line-clamp-2">{review.content}</p>
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                                <Link
                                    href="/reviews"
                                    className="inline-block mt-4 text-orange-600 hover:underline text-sm font-medium"
                                >
                                    View All Reviews →
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AppShell>
    );
}