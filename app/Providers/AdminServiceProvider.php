<?php

namespace App\Providers;

use App\Services\Admin\AdminOffreService;
use App\Services\UserService;
use App\Services\CandidatureService;
use App\Repositories\Interfaces\OffreRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\CandidatureRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
   
    public function register()
    {
        $this->app->singleton(AdminOffreService::class, function ($app) {
            return new AdminOffreService(
                $app->make(OffreRepositoryInterface::class)
            );
        });
        
        $this->app->singleton(UserService::class, function ($app) {
            return new UserService(
                $app->make(UserRepositoryInterface::class)
            );
        });
        
        $this->app->singleton(CandidatureService::class, function ($app) {
            return new CandidatureService(
                $app->make(CandidatureRepositoryInterface::class),
                $app->make(OffreRepositoryInterface::class)
            );
        });
    }

   
    public function boot()
    {
        
    }
}
