<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PdfToImageServiceProvider extends ServiceProvider
{
    protected $defer = false;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Pdf', function($app, $params)
        {
            return new \Spatie\PdfToImage\Pdf($params['pathToFile']);
        });        
    }
}
