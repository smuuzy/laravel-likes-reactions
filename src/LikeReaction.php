<?php

namespace Smuuzy\Laravel\Likes;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Smuuzy\Laravel\Likes\Contracts\Reactor;

/**
 * Smuuzy\Laravel\Likes\LikeReaction
 *
 * @property int $id
 * @property int $user_id
 * @property string $likeable_type
 * @property int $likeable_id
 * @property int $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $likeable
 * @property-read Reactor $reactor
 * @property-read Reactor $user
 * @method static LikeReactionBuilder|LikeReaction newModelQuery()
 * @method static LikeReactionBuilder|LikeReaction newQuery()
 * @method static LikeReactionBuilder|LikeReaction query()
 * @method static LikeReactionBuilder|LikeReaction whereCreatedAt($value)
 * @method static LikeReactionBuilder|LikeReaction whereId($value)
 * @method static LikeReactionBuilder|LikeReaction whereLikeableId($value)
 * @method static LikeReactionBuilder|LikeReaction whereLikeableType($value)
 * @method static LikeReactionBuilder|LikeReaction whereUpdatedAt($value)
 * @method static LikeReactionBuilder|LikeReaction whereUserId($value)
 * @method static LikeReactionBuilder|LikeReaction whereValue($value)
 * @method static LikeReactionBuilder|LikeReaction dislikes()
 * @method static LikeReactionBuilder|LikeReaction likes()
 * @method static LikeReactionBuilder|LikeReaction whereLikableTypeIn(array $types)
 * @mixin Eloquent
 */
class LikeReaction extends Model
{
    const LIKE = 1;
    const DISLIKE = 0;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('reactions.table');

        parent::__construct($attributes);
    }

    public function newEloquentBuilder($query): LikeReactionBuilder
    {
        return new LikeReactionBuilder($query);
    }

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function reactor(): BelongsTo
    {
        return $this->user();
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('reactions.reactor.model'), config('reactions.reactor.foreign_key'));
    }
}
