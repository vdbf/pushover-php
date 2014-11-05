<?php namespace Vdbf\Pushover\Support\Laravel;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Vdbf\Pushover\Pushover;

class PushoverProvider extends ServiceProvider {

    protected $defer = true;

    public function register()
    {
        $this->app['pushover'] = $this->app->share(function($app) {
            return new Pushover(
                $app['config']->get('services.pushover'),
                new Client()
            );
        });
    }

    public function provides()
    {
        return array('pushover');
    }

} 