<?php

namespace Smuuzy\Laravel\Likes\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface Reactor
{
    public function getKey();
    public function reactions(): HasMany;
}
