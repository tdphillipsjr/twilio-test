<?php
/**
 * Script sends a basic text message.
 */

require "twilio-php-latest/Services/Twilio.php";
require "classes/SMSMessage.php";
require "classes/PhoneNumber.php";
require "twilio-config.php";

$accountSid = TWILIO_ACCOUNT_SID; 
$authToken  = TWILIO_AUTH_TOKEN;
$client = new Services_Twilio($accountSid, $authToken);

$sendTo   = isset($_POST['number'])      ? $_POST['number']        : null;
$message  = isset($_POST['textMessage']) ? $_POST['textMessage']   : null;
$callerId = CALLER_ID;
$error = null;

// Some basic error checking.
$error = PhoneNumber::verify($sendTo);
if (!$error) $error = SMSMessage::verify($message);
if ($error) {
    echo $error;
    return;
}

// Send text.
$sms = $client->account->messages->sendMessage($callerId,
					       $sendTo,
					       $message);

echo "Message sent!";

?>
