<?php

require dirname(__DIR__) . '/vendor/autoload.php';

//setup pushover client
$pusher = new \Vdbf\Pushover\Pushover(array('token' => $argv[1]), new \GuzzleHttp\Client());

//compose a message for a recipient with as optional third parameter options like priority and notification sound
$message = new \Vdbf\Pushover\TitledMessage($argv[2], $argv[3], $argv[4]);

//push a message
$pusher->send($message);