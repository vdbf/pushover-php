<?php namespace Vdbf\Pushover;

use Closure;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\RequestInterface;
use Vdbf\Pushover\Exception\InvalidTokenException;
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
        if (!is_null($client)) static::setRequestClientResolver(function () use ($client) {
            return $client;
        });
    }

    /**
     * Sends a message to the pushover service
     * @param Message $message
     * @throws InvalidTokenException
     * @throws RequestClientException
     * @return \GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Message\RequestInterface
     */
    public function send(Message $message)
    {
        $client = $this->resolveRequestClient();
        $request = $client->createRequest('POST', $this->buildTokenUrl(), $message->getOptions());
        return $this->handleRequest($request);
    }

    /**
     * Builds an URL with the supplied token
     * @return string
     * @throws InvalidTokenException
     */
    protected function buildTokenUrl()
    {
        if (!isset($this->config['token'])) {
            throw new InvalidTokenException();
        }

        return static::BASE_URL . '?token=' . $this->config['token'];
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface|\GuzzleHttp\Message\ResponseInterface
     * @throws RequestClientException
     */
    protected function handleRequest(RequestInterface $request)
    {
        if (isset($this->config['batch']) && $this->config['batch']) {
            return $request;
        }
        return $this->resolveRequestClient()->send($request);
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
     * Resolve the RequestClient instance via resolver or return already resolved instance
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
