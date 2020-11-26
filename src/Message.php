<?php

namespace ApiSendSms;

use ApiSendSms\Exceptions\InvalidMessagePriorityException;

class Message
{
	const PRIORITY_LOW = "LOW";
	const PRIORITY_MID = "MID";
	const PRIORITY_HIGH = "HIGH";

	private $recipients = [];
	private $message;
	private $priority;
	private $deadline = null;

	public function __construct()
	{
		$this->priority = self::PRIORITY_LOW;
	}

	/**
	 * @return array
	 */
	public function getRecipients(): array
	{
		return $this->recipients;
	}

	/**
	 * @param array $recipients
	 * @return Message
	 */
	public function addRecipient($recipient): Message
	{
		$recipient = Utils::replace($recipient, '~\s~', '');
		$recipient = Utils::replace($recipient, '~-~', '');
		$recipient = Utils::replace($recipient, '~\(~', '');
		$recipient = Utils::replace($recipient, '~\)~', '');
		$recipient = Utils::replace($recipient, '~#~', '');

		$this->recipients[] = $recipient;
		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getMessage($removeNonAsciChars = true)
	{
		return $removeNonAsciChars ? Utils::toAscii($this->message) : $this->message;
	}

	/**
	 * @param mixed $message
	 * @return Message
	 */
	public function setMessage($message)
	{
		$this->message = $message;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPriority(): string
	{
		return $this->priority;
	}

	/**
	 * @param string $priority
	 * @return Message
	 */
	public function setPriority(string $priority): Message
	{
		if (in_array($priority, [self::PRIORITY_LOW, self::PRIORITY_MID, self::PRIORITY_HIGH])) {
			$this->priority = $priority;
		} else {
			throw new InvalidMessagePriorityException();
		}

		return $this;
	}

	/**
	 * @return null
	 */
	public function getDeadline()
	{
		return $this->deadline;
	}

	/**
	 * accepting all date formats and modifier formats https://www.php.net/manual/en/datetime.formats.php
	 * like as '2020-12-31 00:00:00' or '+2 minutes' or 'next monday' etc
	 *
	 * @param null $deadline
	 * @return Message
	 */
	public function setDeadline(
		$deadline
	) {
		$this->deadline = $deadline;
		return $this;
	}


}