<?php

namespace Smuuzy\Laravel\Likes\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Smuuzy\Laravel\Likes\LikeReaction;

/**
 * @mixin Model
 */
trait Liking
{
    public function reactors(): BelongsToMany
    {
        return $this->belongsToMany(
            config('reactions.reactor.model'),
            config('reactions.table'),
            'likeable_id',
            config('reactions.reactor.foreign_key')
        )
            ->where('likeable_type', $this->getMorphClass());
    }

    public function likeReactions(): HasMany
    {
        return $this->reactions()->where('value', '=', LikeReaction::LIKE);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(LikeReaction::class, 'likeable_id', $this->getKeyName())
            ->where('likeable_type', $this->getMorphClass());
    }

    public function dislikeReactions(): HasMany
    {
        return $this->reactions()->where('value', '=', LikeReaction::DISLIKE);
    }
}
