<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Race;
use App\Policies\RacePolicy;
use App\Models\TrainingPlan;
use App\Policies\TrainingPlanPolicy;
use App\Models\TrainingSession;
use App\Policies\TrainingSessionPolicy;
use App\Models\Vlog;
use App\Policies\VlogPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Race::class, RacePolicy::class);
        Gate::policy(TrainingPlan::class, TrainingPlanPolicy::class);
        Gate::policy(TrainingSession::class, TrainingSessionPolicy::class);
        Gate::policy(Vlog::class, VlogPolicy::class);



    }
}
