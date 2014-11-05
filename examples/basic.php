<?php

require dirname(__DIR__) . '/vendor/autoload.php';

//inject request client via resolver function
\Vdbf\Pushover\Pushover::setRequestClientResolver(function() {
    return new \GuzzleHttp\Client(array(
        //clientconfig
    ));
});



//setup pushover client
$pusher = new \Vdbf\Pushover\Pushover(array(
    'token' => 'token'
));

//compose a message for a recipient with as optional third parameter options like priority and notification sound
$message = new \Vdbf\Pushover\Message('recipient', 'message', array());

//push a message
$pusher->send($message);