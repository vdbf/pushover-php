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
class Client
{

    const BASE_URL = 'https://api.pushover.net/1/messages.json';

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var array
     */
    private $requests = array();

    /**
     * Construct a new pushover instance
     * @param array $config
     * @param ClientInterface $client
     */
    public function __construct(array $config, ClientInterface $client = null)
    {
        $this->config = $config;
        $this->client = $client;
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
        $client = $this->getClient();

        $request = $client->createRequest(
            'POST',
            static::BASE_URL,
            $this->buildRequestOptions($message)
        );

        return $this->handleRequest($request);
    }

    /**
     * Returns an array of requests to enable batching
     * @param callable $batch
     * @return array
     */
    public function batch(Closure $batch)
    {
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
        return $this->getClient()->send($request);
    }

    /**
     * Retrieve the Request Client
     * @return \GuzzleHttp\ClientInterface
     */
    protected function getClient()
    {
        return $this->client;
    }

}
