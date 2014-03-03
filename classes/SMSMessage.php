<?php

/**
 * Class to manage the content of SMS Messages
 *
 * Author: Tom Phillips
 * Date: 3/1/2014
 */

class SMSMessage
{
    public $message;

    public function __construct($text)
    {
        $this->message = $text;
    }

    /**
     * Check to see if a text message fails any validation rules.
     * 
     * @param	string	$text	Text to verify.
     */
    public static function verify($text='')
    {
	// Support a text message of '0'.
	if ($text === '')        return 'Message can not be empty.';

	// Text limit is 160. No support for sending multiple automatically.
        if (strlen($text) > 160) return 'Message too long.';
    }
}
?>
