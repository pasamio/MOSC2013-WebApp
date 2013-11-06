<?php

namespace MOSC\Controller\API;

use Grisgris\Controller\Base;
use Grisgris\Application;

class MemberCreate extends Base
{
	public function execute()
	{
		// Get the collection.
		$collection = $this->provider->get("db")->members;

		// Filter array.
		$filter = array("username" => "string", "name" => "string");
		$data = $this->input->getArray($filter);

		// Validate that we at least got a username.
		if (empty($data['username']))
		{
			$response = new Application\WebResponseBadRequest($this->provider);
			$response->setBody(json_encode(array("message" => "Username is required to create a member.")));
			$this->provider->get('application')->setResponse($response);
			return;
		}

		$response = new Application\WebResponseOk($this->provider);
		$response->setBody(json_encode($this->input->getArray($filter)));
		$this->provider->get('application')->setResponse($response);

		$data['created'] = millitime();

		$message = array(
			"operation" => "create",
			"collection" => "members",
			"data" => $data
		);

		$bytes = $this->provider->get('kafka')->send(array(json_encode($message)), $this->provider->get('app.config')->kafka->topic);

		if ($bytes < 1)
		{
			$response = new Application\WebResponseInternalServerError($this->provider);
			$response->setBody(json_encode(array("message" => "Failed to submit request")));
			$this->provider->get('application')->setResponse($response);
		}
		/*
		$member = $collection->findOne(array("_id" => $this->input->get("id")));

		if ($member == null)
		{
			$response = new Application\WebResponseNotFound($this->provider);
			$response->setBody(json_encode(array("message" => "User not found")));
			$this->provider->get('application')->setResponse($response);
		}
		else
		{
			var_dump($member);
			die("Fail");
		}
		*/
	}
}