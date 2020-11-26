<?php

namespace ApiSendSms;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class Client
{
	/** @var \GuzzleHttp\Client client */
	private $client;
	private $queue;
	/** @var bool if removing non ASCI chars like diacritics etc. */
	private $removeSpecialChars;

	public function __construct($apiKey, $apiSecret, $queue, $removeSpecialChars = true)
	{
		$this->client = new \GuzzleHttp\Client([
			'base_uri' => 'https://app.apisendsms.com',
			\GuzzleHttp\RequestOptions::HEADERS => [
				'Authorization' => 'Key:' . base64_encode(json_encode([
						'key' => $apiKey,
						'secret' => $apiSecret
					])),
				'User-Agent' => 'ApiSendSMS\Client'
			],
		]);
		$this->queue = Utils::replace($queue, '~\s~', '');
		$this->removeSpecialChars = $removeSpecialChars;

	}


	public function sendSms(Message $message)
	{
		$data = [
			'recipients' => $message->getRecipients(),
			"channel" => $this->queue,
			'message' => $message->getMessage($this->removeSpecialChars),
			"priority" => $message->getPriority(),
		];
		if ($message->getDeadline()) {
			$data['exceptedDeadline'] = $message->getDeadline();
		}
		try {
			$response = $this->client->post('/api/v1/messages', [
				RequestOptions::JSON => $data
			]);
			if ($response->getStatusCode() == 201) {
				return true;
			} else {
				return false;
			}
		}
		catch (GuzzleException $e) {
			throw new ApiSendSMSException($e->getMessage(), $e->getCode());
		}
	}


}