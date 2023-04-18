<?php

namespace Smuuzy\Laravel\Likes\Services;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use InvalidArgumentException;
use Smuuzy\Laravel\Likes\Contracts\Likeable;
use Smuuzy\Laravel\Likes\Contracts\Reactor;
use Smuuzy\Laravel\Likes\LikeReaction;

class LikeReactionsService
{
    public function addReaction(Likeable $likeable, Reactor $reactor, bool $value = true): LikeReaction
    {
        return LikeReaction::query()->updateOrCreate([
            config('reactions.reactor.foreign_key') => $reactor->getKey(),
            'likeable_type' => $likeable->getMorphClass(),
            'likeable_id' => $likeable->getKey()
        ], [
            'value' => (int)$value
        ]);
    }

    public function removeReaction(Likeable $likeable, Reactor $reactor): bool
    {
        return LikeReaction::query()->where([
            config('reactions.reactor.foreign_key') => $reactor->getKey(),
            'likeable_type' => $likeable->getMorphClass(),
            'likeable_id' => $likeable->getKey()
        ])->forceDelete();
    }

    public function toggleReaction(Likeable $likeable, Reactor $reactor): LikeReaction
    {
        $reaction = LikeReaction::query()->firstOrNew([
            config('reactions.reactor.foreign_key') => $reactor->getKey(),
            'likeable_type' => $likeable->getMorphClass(),
            'likeable_id' => $likeable->getKey()
        ], [
            'value' => LikeReaction::DISLIKE
        ]);

        $reaction->value = $reaction->value ? LikeReaction::DISLIKE : LikeReaction::LIKE;

        $reaction->save();

        return $reaction;
    }

    public function hasReaction(Likeable $likeable, Reactor $reactor, $value = null): bool
    {
        $reaction = $this->getReaction($likeable, $reactor)->value ?? null;

        return isset($value) ? $reaction === $value : (bool)$reaction;
    }

    public function getReaction(Likeable $likeable, Reactor $reactor)
    {
        return LikeReaction::query()->firstWhere([
            config('reactions.reactor.foreign_key') => $reactor->getKey(),
            'likeable_type' => $likeable->getMorphClass(),
            'likeable_id' => $likeable->getKey()
        ]);
    }

    public function attachReactorReaction(&$likeables, Reactor $reactor)
    {
        $reactions = $reactor->reactions()->get(['likeable_type', 'likeable_id', 'value'])->mapWithKeys(function (LikeReaction $item) {
            return [sprintf('%s:%s', $item->likeable_type, $item->likeable_id) => $item->value];
        });

        $attachStatus = function ($likeable) use ($reactions) {
            $resolver = fn($m) => $m;
            $likeable = $resolver($likeable);

            if ($likeable && $likeable instanceof Likeable) {
                $key = sprintf('%s:%s', $likeable->getMorphClass(), $likeable->getKey());
                $likeable->setAttribute('user_reaction', $reactions->get($key));
            }

            return $likeable;
        };

        switch (true) {
            case $likeables instanceof Model:
                return $attachStatus($likeables);
            case $likeables instanceof Collection:
                return $likeables->each($attachStatus);
            case $likeables instanceof LazyCollection:
                return $likeables = $likeables->map($attachStatus);
//            case $likeables instanceof AbstractPaginator:
//                return $likeables->through($attachStatus);
            case $likeables instanceof Paginator:
                // custom paginator will return a collection
                return collect($likeables->items())->transform($attachStatus);
            case is_array($likeables):
                return collect($likeables)->transform($attachStatus);
            default:
                throw new InvalidArgumentException('Invalid argument type.');
        }
    }
}
