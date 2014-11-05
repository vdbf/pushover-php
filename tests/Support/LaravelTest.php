<?php namespace Vdbf\Pushover\Tests\Support;

use Mockery;
use PHPUnit_Framework_TestCase;
use Vdbf\Pushover\Support\Laravel\PushoverProvider;

class LaravelTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->app = Mockery::mock('ArrayAccess')->shouldIgnoreMissing();
        $this->provider = new PushoverProvider($this->app);
    }

    public function testProvider()
    {
        $this->app->shouldReceive('share')->once();
        $this->provider->register();
    }

}
