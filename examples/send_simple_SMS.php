<?php
include '../vendor/autoload.php';
$x = new \ApiSendSms\Client('apiKey', 'apiSecret', '1234567890', false);

$message = new \ApiSendSms\Message();
$message
	->addRecipient('+420123456789')
	->addRecipient('+420 775-166-226')
	->setMessage('Ahoj')
	->setPriority(\ApiSendSms\Message::PRIORITY_HIGH)
	->setDeadline('+5 minutes');

$x->sendSms($message);