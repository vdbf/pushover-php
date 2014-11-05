<?php namespace Vdbf\Pushover\Tests;

use Mockery;
use Vdbf\Pushover\Client;
use Vdbf\Pushover\Message;
use PHPUnit_Framework_TestCase;

class ClientTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $guzzle;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var \GuzzleHttp\Message\RequestInterface
     */
    protected $request;

    public function setUp()
    {
        $this->guzzle = Mockery::mock('GuzzleHttp\Client');
        $this->request = Mockery::mock('GuzzleHttp\Message\RequestInterface');
        $this->client = new Client(array('token' => 'abc'), $this->guzzle);
    }

    public function testSendingMessage()
    {
        $this->guzzle->shouldReceive('createRequest')->once()->andReturn($this->request);
        $this->guzzle->shouldReceive('send')->once();
        $this->client->send(new Message('recipient', 'text'));
    }

    public function testCreatingMessageBatch()
    {
        $this->guzzle->shouldReceive('createRequest')->twice()->andReturn($this->request);
        $requests = $this->client->batch(function ($pusher) {
            $pusher->send(new Message('recipient', 'text'));
            $pusher->send(new Message('recipient', 'text'));
        });
        $this->assertCount(2, $requests);
    }

    public function testTokenInvalidException()
    {
        $this->setExpectedException('Vdbf\Pushover\Exception\InvalidTokenException');

        //construct the client without token
        $this->client = new Client(array(), $this->guzzle);
        $this->client->send(new Message('recipient', 'text'));
    }

}
