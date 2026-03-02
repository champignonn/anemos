<?php

namespace App\Policies;

use App\Models\Vlog;
use App\Models\User;

class VlogPolicy
{
    public function update(User $user, Vlog $vlog): bool
    {
        return $user->id === $vlog->user_id;
    }

    public function delete(User $user, Vlog $vlog): bool
    {
        return $user->id === $vlog->user_id;
    }
}
