<?php
/**
 * Created by PhpStorm.
 * User: hmduong
 * Date: 11/16/2016
 * Time: 1:22 AM
 */

namespace App\Auth;

use App\Auth\CustomUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class CustomAuthServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        Auth::provider('custom', function($app, array $config) {
            return new CustomUserProvider();
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }
}