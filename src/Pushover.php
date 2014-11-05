<?php namespace Vdbf\Pushover;

use Closure;
use GuzzleHttp\ClientInterface;
use Vdbf\Pushover\Exception\RequestClientException;

/**
 * Class Pushover
 * @package Vdbf\Pushover
 */
class Pushover
{

    const BASE_URL = 'https://api.pushover.net/1/messages.json';

    /**
     * @var ClientInterface
     */
    static $requestClient;

    /**
     * @var Closure
     */
    static $requestClientResolver;

    /**
     * Construct a new pushover instance
     * @param $config
     * @param ClientInterface $client
     */
    public function __construct($config, ClientInterface $client = null)
    {
        $this->config = $config;
        if (!is_null($client)) static::setRequestClientResolver(function () use ($client) {return $client;});
    }

    public function send(Message $message)
    {
        //TODO
    }

    /**
     * Method to inject RequestClient dependency
     * @param callable $resolver
     */
    public static function setRequestClientResolver(Closure $resolver)
    {
        static::$requestClientResolver = $resolver;
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     * @throws RequestClientException
     */
    protected function resolveRequestClient()
    {
        if (isset(static::$requestClient)) {
            return static::$requestClient;
        }

        if (!isset(static::$requestClientResolver)) {
            throw new RequestClientException();
        }

        return static::$requestClient = call_user_func(static::$requestClientResolver);
    }

}
