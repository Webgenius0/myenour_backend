<?php

namespace App\Providers;

use App\Repositories\API\Auth\ForgetPasswordRepository;
use App\Repositories\API\Auth\ForgetPasswordRepositoryInterface;
use App\Repositories\API\Auth\OTPRepository;
use App\Repositories\API\Auth\OTPRepositoryInterface;
use App\Repositories\API\Auth\PasswordRepository;
use App\Repositories\API\Auth\PasswordRepositoryInterface;
use App\Repositories\API\Auth\UserRepository;
use App\Repositories\API\Auth\UserRepositoryInterface;
use App\Repositories\API\InventoryRepository;
use App\Repositories\API\InventoryRepositoryInterface;
use App\Repositories\API\SupplierRepository;
use App\Repositories\API\SupplierRepositoryInterface;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ForgetPasswordRepositoryInterface::class, ForgetPasswordRepository::class);
        $this->app->bind(OTPRepositoryInterface::class, OTPRepository::class);
        $this->app->bind(PasswordRepositoryInterface::class, PasswordRepository::class);
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(InventoryRepositoryInterface::class, InventoryRepository::class);

        $this->app->bind(PDF::class, PDF::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
