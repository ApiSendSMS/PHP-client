<?php

namespace ApiSendSms\Exceptions;

class InvalidMessagePriorityException extends ApiSendSMSException
{
	public function __construct(string $message = "Invalid message priority exception.", int $code = 0, \Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}