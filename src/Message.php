<?php namespace Vdbf\Pushover;

use InvalidArgumentException;

class Message
{

    const PRIORITY_LOWEST = -2;
    const PRIORITY_LOW = -1;
    const PRIORITY_NORMAL = 0;
    const PRIORITY_HIGH = 1;
    const PRIORITY_EMERGENCY = 2;

    /**
     * @var string
     */
    protected $recipient;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var array
     */
    protected $options;

    public function __construct($recipient, $text, $options = array())
    {
        $this->setRecipient($recipient);
        $this->setText($text);
        $this->setOptions($options);
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options)
    {
        $this->checkPropertyType('Options', $options, 'array');
        $this->options = $options;
    }

    /**
     * @return mixed
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param mixed $recipient
     * @throws InvalidArgumentException
     */
    public function setRecipient($recipient)
    {
        $this->checkPropertyType('Recipient', $recipient, 'string');
        $this->recipient = $recipient;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->checkPropertyType('Text', $text, 'string');
        $this->text = $text;
    }

    protected function checkPropertyType($property, $value, $type)
    {
        $checkFn = 'is_' . $type;

        if (!$checkFn($value)) {
            throw new InvalidArgumentException("{$property} should be of type {$type}");
        }
    }

} 