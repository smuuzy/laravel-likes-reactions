<?php

return [
    'table' => 'reactions',

    'reactor' => [
        'table' => 'users',
        'foreign_key' => 'user_id',
        'primary_key' => 'id',
        'primary_key_type' => 'unsignedBigInteger',
        'model' => config('auth.providers.users.model', \App\Models\User::class),
    ]
];
