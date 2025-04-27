<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap styling for the paginator
        Paginator::useBootstrap();
    }

    protected $policies = [
        
        \App\Models\Candidature::class => \App\Policies\CandidaturePolicy::class,
    ];
}
