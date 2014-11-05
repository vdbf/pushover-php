<?php

require dirname(__DIR__) . '/vendor/autoload.php';

//setup pushover client
$pusher = new \Vdbf\Pushover\Pushover(array('token' => $argv[1]), $client = new \GuzzleHttp\Client());

//compose a message for a recipient with as optional third parameter options like priority and notification sound
$message = new \Vdbf\Pushover\Message($argv[2], $argv[3]);

//push a message
$requests = $pusher->batch(function ($pusher) use ($message) {
    $pusher->send($message);
    $pusher->send($message);
    $pusher->send($message);
});

$pool = new \GuzzleHttp\Pool($client, $requests);
$pool->wait();