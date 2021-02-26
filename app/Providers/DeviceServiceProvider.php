<?php

namespace App\Providers;

use App\Http\Repository\Eloquent\DeviceRepository;
use App\Http\Repository\EloquentRepositoryInterface;
use App\Http\Repository\DeviceRepositoryInterface;
use App\Http\Repository\Eloquent\BaseRepository;
use Illuminate\Support\ServiceProvider;

class DeviceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(DeviceRepositoryInterface::class, DeviceRepository::class);
    }

    public function boot()
    {
        //
    }
}
