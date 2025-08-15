<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\FoodProduct
 *
 * @property int $id
 * @property int $brand_id
 * @property string $name
 * @property string|null $description
 * @property string|null $ingredients
 * @property array|null $nutrition_facts
 * @property array|null $allergens
 * @property array|null $certifications
 * @property string|null $manufacturing_location
 * @property string|null $country_of_origin
 * @property array|null $barcodes
 * @property string|null $image_url
 * @property string $average_rating
 * @property int $review_count
 * @property bool $is_hidden
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Brand $brand
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Source> $sources
 * @property-read int|null $sources_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FoodListItem> $listItems
 * @property-read int|null $list_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $reports
 * @property-read int|null $reports_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|FoodProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FoodProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FoodProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|FoodProduct visible()
 * @method static \Illuminate\Database\Eloquent\Builder|FoodProduct search($query)
 * @method static \Database\Factories\FoodProductFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class FoodProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'brand_id',
        'name',
        'description',
        'ingredients',
        'nutrition_facts',
        'allergens',
        'certifications',
        'manufacturing_location',
        'country_of_origin',
        'barcodes',
        'image_url',
        'average_rating',
        'review_count',
        'is_hidden',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'nutrition_facts' => 'array',
        'allergens' => 'array',
        'certifications' => 'array',
        'barcodes' => 'array',
        'average_rating' => 'decimal:2',
        'is_hidden' => 'boolean',
    ];

    /**
     * Get the brand that owns this food product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the reviews for this food product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the sources linked to this food product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sources(): BelongsToMany
    {
        return $this->belongsToMany(Source::class, 'food_product_sources')
            ->withPivot('field_type')
            ->withTimestamps();
    }

    /**
     * Get the list items for this food product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function listItems(): HasMany
    {
        return $this->hasMany(FoodListItem::class);
    }

    /**
     * Get all reports for this food product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Scope a query to only include visible food products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }

    /**
     * Scope a query to search food products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $searchQuery
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $searchQuery)
    {
        return $query->where(function ($q) use ($searchQuery) {
            $q->where('name', 'like', '%' . $searchQuery . '%')
              ->orWhere('description', 'like', '%' . $searchQuery . '%')
              ->orWhere('ingredients', 'like', '%' . $searchQuery . '%');
        });
    }
}