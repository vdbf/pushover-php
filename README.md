pushover-php
============

Pushover REST API implementation in PHP

##Example usage

```php

require dirname(__DIR__) . '/vendor/autoload.php';

//setup pushover client
$pusher = new \Vdbf\Pushover\Client(array('token' => $argv[1]), new \GuzzleHttp\Client());

//compose a message
$message = new \Vdbf\Pushover\Message($argv[2], $argv[3]);

//push a message
$pusher->send($message);

```

## Integration
At the moment of writing, integration for Laravel 4.* is supported. A service provider and a facade class are supplied. Installation is done in 2 simple steps after the general installation steps:

1. edit `app/config/app.php` to add the service provider and the facade class
```
    'providers' => array(
      ...
      'Vdbf\Pushover\Support\Laravel\PushoverProvider'
    )
    
    'aliases' => array(
      ...
      'Pushover' => 'Vdbf\Pushover\Support\Laravel\PushoverFacade'
    )
```
2. edit `app/config/services.php` (supplied by default from L4.2) to add a `token` setting
```
    'pushover' => array(
      'token' => YOUR_PUSHOVER_APP_TOKEN
    )
```
