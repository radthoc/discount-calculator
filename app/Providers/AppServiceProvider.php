<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Services\Interfaces\FileServiceInterface',
            'App\Services\FileService'
        );

        $this->app->bind(
            'App\Services\DiscountCalculator\Interfaces\DiscountCalculatorInterface',
            'App\Services\DiscountCalculator\DiscountCalculator'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
