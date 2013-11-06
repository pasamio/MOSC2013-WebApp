<?php

namespace MOSC\Controller\API;

use Grisgris\Controller\Base;
use Grisgris\Application;
use MOSC\Member\Member;

class MemberUpdate extends Base
{
	public function execute()
	{
		// Get the collection.
		$collection = $this->provider->get("db")->members;

		// Filter array.
		$filter = array("username" => "string", "name" => "string", "id" => "string", "status" => "string");
		$data = $this->input->getArray($filter);

		// Prevent people from changing their username.
		unset($data['username']);

		// Remove any empty fields.
		foreach ($data as $key => $value)
		{
			if (empty($value))
			{
				unset($data[$key]);
			}
		}

		// Validate that we at least got an ID to use.
		$id = $this->input->get("id");
		if (empty($id))
		{
			$response = new Application\WebResponseBadRequest($this->provider);
			$response->setBody(json_encode(array("message" => "User ID is required to create a member.")));
			$this->provider->get('application')->setResponse($response);
			return;
		}

		unset($data['id']);
		$data['updated'] = millitime();

		$member = new Member($this->provider);
		if ($member->update($id, $data))
		{
			$response = new Application\WebResponseOk($this->provider);
			$response->setBody(json_encode($data));
			$this->provider->get('application')->setResponse($response);
		}
		else
		{
			$response = new Application\WebResponseInternalServerError($this->provider);
			$response->setBody(json_encode(array("message" => "Failed to submit request")));
			$this->provider->get('application')->setResponse($response);
		}
	}
}