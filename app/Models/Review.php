<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\Review
 *
 * @property int $id
 * @property int $user_id
 * @property int $food_product_id
 * @property int $rating
 * @property string $content
 * @property array|null $images
 * @property int $likes_count
 * @property int $comments_count
 * @property bool $is_hidden
 * @property \Illuminate\Support\Carbon|null $hidden_at
 * @property int|null $hidden_by
 * @property string|null $hidden_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\FoodProduct $foodProduct
 * @property-read \App\Models\User|null $hiddenBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count_relation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reaction> $reactions
 * @property-read int|null $reactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $reports
 * @property-read int|null $reports_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder|Review visible()
 * @method static \Database\Factories\ReviewFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'food_product_id',
        'rating',
        'content',
        'images',
        'likes_count',
        'comments_count',
        'is_hidden',
        'hidden_at',
        'hidden_by',
        'hidden_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'images' => 'array',
        'is_hidden' => 'boolean',
        'hidden_at' => 'datetime',
    ];

    /**
     * Get the user who wrote this review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the food product this review is for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function foodProduct(): BelongsTo
    {
        return $this->belongsTo(FoodProduct::class);
    }

    /**
     * Get the user who hid this review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hiddenBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hidden_by');
    }

    /**
     * Get the comments for this review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get the reactions for this review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    /**
     * Get all reports for this review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Scope a query to only include visible reviews.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }
}