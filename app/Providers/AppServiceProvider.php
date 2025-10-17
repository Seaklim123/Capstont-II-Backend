<?php

namespace App\Providers;

use App\Repositories\CategoryRepositories;
use App\Repositories\implement\TableNumberRepository;
use App\Repositories\Interfaces\CategoryRepositoriesInterfaces;
use App\Repositories\Interfaces\ProductRepositoriesInterfaces;
use App\Repositories\Interfaces\TableNumberRepositoryInterface;
use App\Repositories\ProductRepositories;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(CategoryRepositoriesInterfaces::class , CategoryRepositories::class);
        $this->app->bind(ProductRepositoriesInterfaces::class , ProductRepositories::class);
        $this->app->bind(TableNumberRepositoryInterface::class , TableNumberRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
