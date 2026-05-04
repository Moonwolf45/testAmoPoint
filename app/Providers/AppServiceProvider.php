<?php

namespace App\Providers;

use App\Repositories\VisitRepository;
use App\Services\IpLocationService;
use App\Services\StatsService;
use App\Services\UserAgentParserService;
use App\Services\VisitTrackerService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(VisitRepository::class);
        $this->app->singleton(IpLocationService::class);
        $this->app->singleton(UserAgentParserService::class);
        $this->app->singleton(VisitTrackerService::class);
        $this->app->singleton(StatsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
