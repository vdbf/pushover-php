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
     * @var array
     */
    private $requests;

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

        $request = $client->createRequest(
            'POST',
            static::BASE_URL,
            $this->buildRequestOptions($message)
        );

        return $this->handleRequest($request);
    }

    public function batch(Closure $batch)
    {
        $this->requests = array();
        $this->config['batch'] = true;

        call_user_func($batch, $this);

        $requests = $this->requests;
        $this->requests = array();

        return $requests;
    }

    /**
     * Builds request options
     * @param Message $message
     * @return array
     * @throws InvalidTokenException
     */
    protected function buildRequestOptions(Message $message)
    {
        if (!isset($this->config['token'])) {
            throw new InvalidTokenException();
        }

        $debug = isset($this->config['debug']) ? $this->config['debug'] : false;

        $body = array_merge(
            $message->getOptions(),
            array(
                'user' => $message->getRecipient(),
                'message' => $message->getText(),
                'token' => $this->config['token']
            )
        );

        return compact('body', 'debug');
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface|\GuzzleHttp\Message\ResponseInterface
     * @throws RequestClientException
     */
    protected function handleRequest(RequestInterface $request)
    {
        if (isset($this->config['batch']) && $this->config['batch']) {
            return $this->requests[] = $request;
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
