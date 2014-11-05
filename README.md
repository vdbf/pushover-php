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
