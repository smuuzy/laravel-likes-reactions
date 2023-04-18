<?php

namespace Smuuzy\Laravel\Likes\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface Likeable
{
    public function getMorphClass();

    public function getKey();

    public function reactors(): BelongsToMany;

    public function reactions(): HasMany;

    public function likeReactions(): HasMany;

    public function dislikeReactions(): HasMany;
}
