<?php

namespace Smuuzy\Laravel\Likes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
class LikeReactionBuilder extends Builder
{

    public function likes(): self
    {
        return $this->where('reactions.value', '=', LikeReaction::LIKE);
    }

    public function dislikes(): self
    {
        return $this->where('reactions.value', '=', LikeReaction::DISLIKE);
    }

    public function whereLikableTypeIn(array $types): self
    {
        return $this->whereIn('reactions.likeable_type', $types);
    }
}
