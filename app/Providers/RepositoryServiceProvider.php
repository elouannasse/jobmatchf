<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Interfaces
use App\Repositories\Interfaces\EloquentRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\OffreRepositoryInterface;
use App\Repositories\Interfaces\CandidatureRepositoryInterface;
use App\Repositories\Interfaces\MessageRepositoryInterface;

// Repositories
use App\Repositories\BaseRepository;
use App\Repositories\UserRepository;
use App\Repositories\OffreRepository;
use App\Repositories\CandidatureRepository;
use App\Repositories\MessageRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(OffreRepositoryInterface::class, OffreRepository::class);
        $this->app->bind(CandidatureRepositoryInterface::class, CandidatureRepository::class);
        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
