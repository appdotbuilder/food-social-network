<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\FoodListItem
 *
 * @property int $id
 * @property int $food_list_id
 * @property int $food_product_id
 * @property string|null $note
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FoodList $foodList
 * @property-read \App\Models\FoodProduct $foodProduct
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|FoodListItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FoodListItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FoodListItem query()
 * @method static \Database\Factories\FoodListItemFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class FoodListItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'food_list_id',
        'food_product_id',
        'note',
        'order',
    ];

    /**
     * Get the food list this item belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function foodList(): BelongsTo
    {
        return $this->belongsTo(FoodList::class);
    }

    /**
     * Get the food product for this item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function foodProduct(): BelongsTo
    {
        return $this->belongsTo(FoodProduct::class);
    }
}