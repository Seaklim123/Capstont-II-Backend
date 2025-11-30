<?php

namespace App\Providers;

use App\Repositories\CartRepositories;
use App\Repositories\CategoryRepositories;
use App\Repositories\implement\DashboardRepository;
use App\Repositories\implement\ReportRepository;
use App\Repositories\implement\TableNumberRepositoryImplementation;
use App\Repositories\implement\UserRepositoryImplementation;
use App\Repositories\Interfaces\CartRepositoriesInterfaces;
use App\Repositories\Interfaces\CategoryRepositoriesInterfaces;
use App\Repositories\Interfaces\DashboardRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoriesInterfaces;
use App\Repositories\Interfaces\ProductRepositoriesInterfaces;
use App\Repositories\Interfaces\ReportRepositoryInterface;
use App\Repositories\Interfaces\TableNumberRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\OrderRepositories;
use App\Repositories\ProductRepositories;
use App\Services\implementation\DashboardService;
use App\Services\implementation\ReportService;
use App\Services\implementation\UserServiceImplementation;
use App\Services\Interface\DashboardServiceInterface;
use App\Services\Interface\ReportServiceInterface;
use App\Services\Interface\UserServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(UserRepositoryInterface::class , UserRepositoryImplementation::class);
        $this->app->bind(UserServiceInterface::class , UserServiceImplementation::class);
        $this->app->bind(TableNumberRepositoryInterface::class , TableNumberRepositoryImplementation::class);
        $this->app->bind(CategoryRepositoriesInterfaces::class , CategoryRepositories::class);
        $this->app->bind(ProductRepositoriesInterfaces::class , ProductRepositories::class);
        $this->app->bind(CartRepositoriesInterfaces::class , CartRepositories::class);
        $this->app->bind(OrderRepositoriesInterfaces::class , OrderRepositories::class);
        $this->app->bind(DashboardRepositoryInterface::class , DashboardRepository::class);
        $this->app->bind(DashboardServiceInterface::class , DashboardService::class);
        $this->app->bind(ReportRepositoryInterface::class , ReportRepository::class);
        $this->app->bind(ReportServiceInterface::class , ReportService::class);
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
