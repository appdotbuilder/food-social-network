<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\ModerationLog
 *
 * @property int $id
 * @property int $moderator_id
 * @property string $target_type
 * @property int $target_id
 * @property string $action
 * @property string $reason
 * @property array|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $moderator
 * @property-read Model|\Eloquent $target
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|ModerationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModerationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModerationLog query()
 * @method static \Database\Factories\ModerationLogFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class ModerationLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'moderator_id',
        'target_type',
        'target_id',
        'action',
        'reason',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the moderator who performed this action.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

    /**
     * Get the target of the moderation action.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function target(): MorphTo
    {
        return $this->morphTo();
    }
}