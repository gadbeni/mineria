<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;
use App\FormFields\register_userIdFormField;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Voyager::addFormField(register_userIdFormField::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        
    }
}
