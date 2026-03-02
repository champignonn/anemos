<?php

namespace App\Policies;

use App\Models\TrainingPlan;
use App\Models\User;

class TrainingPlanPolicy
{
    public function view(User $user, TrainingPlan $trainingPlan): bool
    {
        return $user->id === $trainingPlan->user_id;
    }

    public function delete(User $user, TrainingPlan $trainingPlan): bool
    {
        return $user->id === $trainingPlan->user_id;
    }
}
