<?php namespace Vdbf\Pushover;

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
     */
    public function setRecipient($recipient)
    {
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
        $this->text = $text;
    }

} 