import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface Source {
    id: number;
    type: string;
    title: string;
    url: string | null;
    verified_at: string | null;
    pivot: {
        field_type: string;
    };
}

interface Review {
    id: number;
    rating: number;
    content: string;
    images: string[] | null;
    likes_count: number;
    comments_count: number;
    created_at: string;
    user: {
        id: number;
        name: string;
        profile: {
            display_name: string | null;
            avatar_url: string | null;
        } | null;
    };
    comments: Array<{
        id: number;
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
    }>;
}

interface FoodProduct {
    id: number;
    name: string;
    description: string | null;
    ingredients: string | null;
    nutrition_facts: Record<string, string | number> | null;
    allergens: string[] | null;
    certifications: string[] | null;
    manufacturing_location: string | null;
    country_of_origin: string | null;
    barcodes: string[] | null;
    image_url: string | null;
    average_rating: number;
    review_count: number;
    brand: {
        id: number;
        name: string;
        company: {
            id: number;
            name: string;
            website: string | null;
        };
    };
    sources: Source[];
    reviews: Review[];
}

interface Props {
    foodProduct: FoodProduct;
    [key: string]: unknown;
}

export default function Show({ foodProduct }: Props) {
    const renderStars = (rating: number) => {
        return Array.from({ length: 5 }, (_, i) => (
            <span key={i} className={i < Math.floor(rating) ? 'star-filled' : 'star-empty'}>
                ⭐
            </span>
        ));
    };

    const getSourceBadgeStyle = (type: string) => {
        switch (type) {
            case 'website':
                return 'bg-blue-100 text-blue-800';
            case 'pdf':
                return 'bg-red-100 text-red-800';
            case 'product_label':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };

    const renderNutritionFacts = (facts: Record<string, string | number>) => {
        return Object.entries(facts).map(([key, value]) => (
            <div key={key} className="flex justify-between py-1 border-b border-gray-100 last:border-b-0">
                <span className="capitalize font-medium text-gray-700">{key.replace('_', ' ')}</span>
                <span className="text-gray-900">{value}</span>
            </div>
        ));
    };

    return (
        <AppShell>
            <Head title={`${foodProduct.name} - ${foodProduct.brand.name}`} />
            
            <div className="container mx-auto px-4 py-8">
                {/* Breadcrumb */}
                <nav className="mb-8">
                    <ol className="flex items-center space-x-2 text-sm text-gray-500">
                        <li><Link href="/" className="hover:text-orange-600">🏠 Home</Link></li>
                        <li>/</li>
                        <li><Link href="/food-products" className="hover:text-orange-600">Food Products</Link></li>
                        <li>/</li>
                        <li className="text-gray-900 font-medium">{foodProduct.name}</li>
                    </ol>
                </nav>

                <div className="grid lg:grid-cols-2 gap-12">
                    {/* Product Image */}
                    <div className="space-y-4">
                        <div className="aspect-square bg-gray-100 rounded-xl overflow-hidden">
                            {foodProduct.image_url ? (
                                <img
                                    src={foodProduct.image_url}
                                    alt={foodProduct.name}
                                    className="w-full h-full object-cover"
                                />
                            ) : (
                                <div className="w-full h-full flex items-center justify-center text-6xl">
                                    🍽️
                                </div>
                            )}
                        </div>
                        
                        {/* Additional Images from Reviews */}
                        {foodProduct.reviews.some(review => review.images && review.images.length > 0) && (
                            <div className="grid grid-cols-4 gap-2">
                                {foodProduct.reviews
                                    .flatMap(review => review.images || [])
                                    .slice(0, 8)
                                    .map((image, index) => (
                                        <div key={index} className="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                            <img
                                                src={image}
                                                alt="User review"
                                                className="w-full h-full object-cover"
                                            />
                                        </div>
                                    ))}
                            </div>
                        )}
                    </div>

                    {/* Product Details */}
                    <div className="space-y-8">
                        {/* Basic Info */}
                        <div>
                            <div className="mb-2">
                                <p className="text-sm text-gray-500">
                                    {foodProduct.brand.company.name} • {foodProduct.brand.name}
                                </p>
                                <h1 className="text-3xl font-bold text-gray-900">{foodProduct.name}</h1>
                            </div>
                            
                            {/* Rating */}
                            <div className="flex items-center gap-4 mb-4">
                                <div className="rating-stars">
                                    {renderStars(foodProduct.average_rating)}
                                </div>
                                <span className="text-lg font-semibold">
                                    {foodProduct.average_rating.toFixed(1)}
                                </span>
                                <span className="text-gray-600">
                                    ({foodProduct.review_count} reviews)
                                </span>
                            </div>

                            {foodProduct.description && (
                                <p className="text-gray-700 leading-relaxed">{foodProduct.description}</p>
                            )}
                        </div>

                        {/* Badges */}
                        <div className="space-y-4">
                            {foodProduct.certifications && foodProduct.certifications.length > 0 && (
                                <div>
                                    <h3 className="text-sm font-semibold text-gray-900 mb-2">🏆 Certifications</h3>
                                    <div className="flex flex-wrap gap-2">
                                        {foodProduct.certifications.map((cert) => (
                                            <span key={cert} className="badge-certification">
                                                {cert}
                                            </span>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {foodProduct.allergens && foodProduct.allergens.length > 0 && (
                                <div>
                                    <h3 className="text-sm font-semibold text-gray-900 mb-2">⚠️ Contains Allergens</h3>
                                    <div className="flex flex-wrap gap-2">
                                        {foodProduct.allergens.map((allergen) => (
                                            <span key={allergen} className="badge-allergen">
                                                {allergen}
                                            </span>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Quick Facts */}
                        <div className="bg-gray-50 p-4 rounded-lg">
                            <h3 className="text-lg font-semibold text-gray-900 mb-3">📍 Product Info</h3>
                            <div className="space-y-2 text-sm">
                                {foodProduct.country_of_origin && (
                                    <div>
                                        <span className="font-medium">Country of Origin:</span>
                                        <span className="ml-2">{foodProduct.country_of_origin}</span>
                                    </div>
                                )}
                                {foodProduct.manufacturing_location && (
                                    <div>
                                        <span className="font-medium">Manufactured in:</span>
                                        <span className="ml-2">{foodProduct.manufacturing_location}</span>
                                    </div>
                                )}
                                {foodProduct.brand.company.website && (
                                    <div>
                                        <span className="font-medium">Company:</span>
                                        <a 
                                            href={foodProduct.brand.company.website}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="ml-2 text-orange-600 hover:underline"
                                        >
                                            {foodProduct.brand.company.name} ↗
                                        </a>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Action Buttons */}
                        <div className="flex gap-4">
                            <Link 
                                href={`/reviews/create?food_product_id=${foodProduct.id}`}
                                className="btn-primary"
                            >
                                ✍️ Write Review
                            </Link>
                            <button className="btn-secondary">
                                ❤️ Add to List
                            </button>
                        </div>
                    </div>
                </div>

                {/* Detailed Information Tabs */}
                <div className="mt-16">
                    <div className="grid lg:grid-cols-3 gap-8">
                        {/* Ingredients */}
                        {foodProduct.ingredients && (
                            <div className="bg-white rounded-xl shadow-sm p-6">
                                <h3 className="text-xl font-bold text-gray-900 mb-4">🥗 Ingredients</h3>
                                <p className="text-gray-700 text-sm leading-relaxed">
                                    {foodProduct.ingredients}
                                </p>
                                
                                {/* Sources for ingredients */}
                                {foodProduct.sources.filter(s => s.pivot.field_type === 'ingredients').length > 0 && (
                                    <div className="mt-4">
                                        <p className="text-xs font-medium text-gray-500 mb-2">Sources:</p>
                                        <div className="space-y-1">
                                            {foodProduct.sources
                                                .filter(s => s.pivot.field_type === 'ingredients')
                                                .map(source => (
                                                    <div key={source.id} className="flex items-center gap-2">
                                                        <span className={`text-xs px-2 py-1 rounded ${getSourceBadgeStyle(source.type)}`}>
                                                            {source.type}
                                                        </span>
                                                        {source.url ? (
                                                            <a 
                                                                href={source.url}
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                className="text-xs text-orange-600 hover:underline"
                                                            >
                                                                {source.title} ↗
                                                            </a>
                                                        ) : (
                                                            <span className="text-xs text-gray-600">{source.title}</span>
                                                        )}
                                                        {source.verified_at && (
                                                            <span className="text-xs text-green-600">✓ Verified</span>
                                                        )}
                                                    </div>
                                                ))}
                                        </div>
                                    </div>
                                )}
                            </div>
                        )}

                        {/* Nutrition Facts */}
                        {foodProduct.nutrition_facts && (
                            <div className="bg-white rounded-xl shadow-sm p-6">
                                <h3 className="text-xl font-bold text-gray-900 mb-4">📊 Nutrition Facts</h3>
                                <div className="space-y-1">
                                    {renderNutritionFacts(foodProduct.nutrition_facts)}
                                </div>

                                {/* Sources for nutrition */}
                                {foodProduct.sources.filter(s => s.pivot.field_type === 'nutrition').length > 0 && (
                                    <div className="mt-4">
                                        <p className="text-xs font-medium text-gray-500 mb-2">Sources:</p>
                                        <div className="space-y-1">
                                            {foodProduct.sources
                                                .filter(s => s.pivot.field_type === 'nutrition')
                                                .map(source => (
                                                    <div key={source.id} className="flex items-center gap-2">
                                                        <span className={`text-xs px-2 py-1 rounded ${getSourceBadgeStyle(source.type)}`}>
                                                            {source.type}
                                                        </span>
                                                        {source.url ? (
                                                            <a 
                                                                href={source.url}
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                className="text-xs text-orange-600 hover:underline"
                                                            >
                                                                {source.title} ↗
                                                            </a>
                                                        ) : (
                                                            <span className="text-xs text-gray-600">{source.title}</span>
                                                        )}
                                                        {source.verified_at && (
                                                            <span className="text-xs text-green-600">✓ Verified</span>
                                                        )}
                                                    </div>
                                                ))}
                                        </div>
                                    </div>
                                )}
                            </div>
                        )}

                        {/* Product Identifiers */}
                        {foodProduct.barcodes && foodProduct.barcodes.length > 0 && (
                            <div className="bg-white rounded-xl shadow-sm p-6">
                                <h3 className="text-xl font-bold text-gray-900 mb-4">🏷️ Product Codes</h3>
                                <div className="space-y-2">
                                    {foodProduct.barcodes.map((barcode, index) => (
                                        <div key={index} className="font-mono text-sm bg-gray-100 p-2 rounded">
                                            {barcode}
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                {/* Reviews Section */}
                <div className="mt-16">
                    <div className="flex items-center justify-between mb-8">
                        <h2 className="text-2xl font-bold text-gray-900">💬 Reviews ({foodProduct.review_count})</h2>
                        <Link 
                            href={`/reviews/create?food_product_id=${foodProduct.id}`}
                            className="btn-primary"
                        >
                            ✍️ Write Review
                        </Link>
                    </div>

                    <div className="space-y-6">
                        {foodProduct.reviews.map((review) => (
                            <div key={review.id} className="review-card">
                                <div className="flex items-start gap-4">
                                    <div className="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        {review.user.profile?.avatar_url ? (
                                            <img
                                                src={review.user.profile.avatar_url}
                                                alt={review.user.name}
                                                className="w-12 h-12 rounded-full object-cover"
                                            />
                                        ) : (
                                            <span className="text-orange-600 font-semibold text-lg">
                                                {review.user.name.charAt(0).toUpperCase()}
                                            </span>
                                        )}
                                    </div>
                                    
                                    <div className="flex-1">
                                        <div className="flex items-center gap-3 mb-2">
                                            <span className="font-semibold">
                                                {review.user.profile?.display_name || review.user.name}
                                            </span>
                                            <div className="rating-stars">
                                                {renderStars(review.rating)}
                                            </div>
                                            <span className="text-sm text-gray-500">
                                                {new Date(review.created_at).toLocaleDateString()}
                                            </span>
                                        </div>
                                        
                                        <p className="text-gray-700 mb-3">{review.content}</p>
                                        
                                        {review.images && review.images.length > 0 && (
                                            <div className="flex gap-2 mb-3">
                                                {review.images.map((image, index) => (
                                                    <img
                                                        key={index}
                                                        src={image}
                                                        alt="Review"
                                                        className="w-20 h-20 object-cover rounded-lg"
                                                    />
                                                ))}
                                            </div>
                                        )}
                                        
                                        <div className="flex items-center gap-4 text-sm text-gray-500">
                                            <button className="hover:text-orange-600">
                                                👍 {review.likes_count}
                                            </button>
                                            <button className="hover:text-orange-600">
                                                💬 {review.comments_count} comments
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AppShell>
    );
}