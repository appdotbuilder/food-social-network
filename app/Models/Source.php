<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Source
 *
 * @property int $id
 * @property string $type
 * @property string $title
 * @property string|null $url
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FoodProduct> $foodProducts
 * @property-read int|null $food_products_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Source newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Source newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Source query()
 * @method static \Illuminate\Database\Eloquent\Builder|Source verified()
 * @method static \Database\Factories\SourceFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Source extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'type',
        'title',
        'url',
        'description',
        'verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * Get the food products linked to this source.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function foodProducts(): BelongsToMany
    {
        return $this->belongsToMany(FoodProduct::class, 'food_product_sources')
            ->withPivot('field_type')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include verified sources.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }
}