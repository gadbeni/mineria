<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;
use App\FormFields\register_userIdFormField;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;


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
        // Fuerza a Voyager a usar App\Models\User (con SoftDeletes)
        // en lugar de TCG\Voyager\Models\User en todo el panel.
        Voyager::useModel('User', \App\Models\User::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', true);
        }
        Paginator::useBootstrap();
        
    }
}
