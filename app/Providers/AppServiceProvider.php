<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use App\Helpers\DateHelper;

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

        // $this->app->bind(
        //     'DateHelper',
        //     function () {
        //         return new DateHelper();
        //     }
        // );
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
