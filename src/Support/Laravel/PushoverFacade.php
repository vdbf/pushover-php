<?php namespace Vdbf\Pushover\Support\Laravel;

use Illuminate\Support\Facades\Facade;

class PushoverFacade extends Facade {

    public function getFacadeAccessor()
    {
        return 'pushover';
    }

} 