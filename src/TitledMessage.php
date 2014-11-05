<?php namespace Vdbf\Pushover; 

class TitledMessage extends Message {

    public function __construct($recipient, $title, $text, $options = array())
    {
        $options['title'] = $title;
        parent::__construct($recipient, $text, $options);
    }

} 