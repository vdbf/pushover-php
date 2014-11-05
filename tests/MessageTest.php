<?php namespace Vdbf\Pushover\Tests;

use PHPUnit_Framework_TestCase;
use Vdbf\Pushover\Message;
use Vdbf\Pushover\TitledMessage;

class MessageTest extends PHPUnit_Framework_TestCase
{

    public function testValidConstructionInput()
    {
        $message = new Message('recipient', 'text');
        $this->assertInstanceOf('Vdbf\Pushover\Message', $message);
    }

    public function testInvalidConstructionInput()
    {
        $this->setExpectedException('Exception', 'Recipient should be of type string');
        new Message(123, 'text');

        $this->setExpectedException('Exception', 'Text should be of type string');
        new Message('recipient', 123);

        $this->setExpectedException('Exception', 'Options should be of type array');
        new Message('recipient', 'text', 123);
    }

    public function testTitledMessage()
    {
        $message = new TitledMessage('recipient', 'title', 'text');
        $this->assertArrayHasKey('title', $message->getOptions());
    }

}
