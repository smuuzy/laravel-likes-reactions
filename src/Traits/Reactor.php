<?php

namespace Smuuzy\Laravel\Likes\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Smuuzy\Laravel\Likes\LikeReaction;

/**
 * @mixin Model
 */
trait Reactor
{
    public function reactions(): HasMany
    {
        return $this->hasMany(LikeReaction::class, config('reactions.reactor.foreign_key'), $this->getKeyName());
    }
}
