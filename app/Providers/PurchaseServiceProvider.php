<?php

namespace App\Providers;

use App\Http\Repository\DeviceRepositoryInterface;
use App\Http\Repository\Eloquent\DeviceRepository;
use Illuminate\Support\ServiceProvider;
use App\Http\Repository\Eloquent\PurchaseRepository;
use App\Http\Repository\PurchaseRepositoryInterface;
use App\Http\Repository\EloquentRepositoryInterface;
use App\Http\Repository\Eloquent\BaseRepository;

class PurchaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(PurchaseRepositoryInterface::class, PurchaseRepository::class);
        $this->app->bind(DeviceRepositoryInterface::class, DeviceRepository::class);
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
