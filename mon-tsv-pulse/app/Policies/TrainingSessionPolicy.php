<?php

namespace App\Policies;

use App\Models\TrainingSession;
use App\Models\User;

class TrainingSessionPolicy
{
    public function view(User $user, TrainingSession $session): bool
    {
        return $user->id === $session->user_id;
    }

    public function update(User $user, TrainingSession $session): bool
    {
        return $user->id === $session->user_id;
    }

    public function delete(User $user, TrainingSession $session): bool
    {
        return $user->id === $session->user_id;
    }
}
