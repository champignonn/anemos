<?php

namespace App\Policies;

use App\Models\Race;
use App\Models\User;

class RacePolicy
{
    public function view(User $user, Race $race): bool
    {
        return $user->id === $race->user_id;
    }

    public function update(User $user, Race $race): bool
    {
        return $user->id === $race->user_id;
    }

    public function delete(User $user, Race $race): bool
    {
        return $user->id === $race->user_id;
    }
}
