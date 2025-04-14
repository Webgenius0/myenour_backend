<?php

namespace App\Providers;

use App\Models\EventInventoryAssignment;
use App\Repositories\API\Auth\ForgetPasswordRepository;
use App\Repositories\API\Auth\ForgetPasswordRepositoryInterface;
use App\Repositories\API\Auth\OTPRepository;
use App\Repositories\API\Auth\OTPRepositoryInterface;
use App\Repositories\API\Auth\PasswordRepository;
use App\Repositories\API\Auth\PasswordRepositoryInterface;
use App\Repositories\API\Auth\UserRepository;
use App\Repositories\API\Auth\UserRepositoryInterface;
use App\Repositories\API\DailyTrackingRepository;
use App\Repositories\API\DailyTrackingRepositoryInterface;
use App\Repositories\API\EventInventoryRepository;
use App\Repositories\API\EventInventoryRepositoryInterface;
use App\Repositories\API\EventRepository;
use App\Repositories\API\EventRepositoryInterface;
use App\Repositories\API\InventoryRepository;
use App\Repositories\API\InventoryRepositoryInterface;
use App\Repositories\API\OrderRepository;
use App\Repositories\API\OrderRepositoryInterface;
use App\Repositories\API\ReportRepository;
use App\Repositories\API\ReportRepositoryInterface;
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
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
        $this->app->bind(DailyTrackingRepositoryInterface::class, DailyTrackingRepository::class);
        $this->app->bind(EventInventoryRepositoryInterface::class, EventInventoryRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);

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
