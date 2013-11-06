<?php

namespace MOSC\Controller\API;

use Grisgris\Controller\Base;
use Grisgris\Application;
use UUID;

class MessageCreate extends Base
{
	public function execute()
	{

		// Get the collection.
		$collection = $this->provider->get("db")->members;

		// Filter array.
		$filter = array("user_id" => "string", "message" => "string", "parent_id" => "string");
		$data = $this->input->getArray($filter);

		// Validate that we at least got a username.
		if (empty($data['message']))
		{
			$response = new Application\WebResponseBadRequest($this->provider);
			$response->setBody(json_encode(array("message" => "Message is required to create a reply.")));
			$this->provider->get('application')->setResponse($response);
			return;
		}

		$uuid = UUID::mint()->string;
		$message = array(
			"operation" => "create",
			"collection" => "messages",
			"data" => array(
				'_id' => $uuid,
				'message_id' => $uuid,
				'user_id' => $data['user_id'], 
				'message' => $data['message'], 
				'created' => millitime(),
				'parent_id' => $data['parent_id'])
		);

		$response = new Application\WebResponseOk($this->provider);
		$response->setBody(json_encode($message['data']));
		$this->provider->get('application')->setResponse($response);

		$bytes = $this->provider->get('kafka')->send(array(json_encode($message)), $this->provider->get('app.config')->kafka->topic);

		if ($bytes < 1)
		{
			$response = new Application\WebResponseInternalServerError($this->provider);
			$response->setBody(json_encode(array("message" => "Failed to submit request")));
			$this->provider->get('application')->setResponse($response);
		}
	}
}