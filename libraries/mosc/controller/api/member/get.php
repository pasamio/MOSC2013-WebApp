<?php

namespace MOSC\Controller\API;

use Grisgris\Controller\Base;
use Grisgris\Application;

use MongoId;

class MemberGet extends Base
{
	public function execute()
	{
		$member = new Member($this->provider)->get($this->input->get("id"));

		if ($member == null)
		{
			$response = new Application\WebResponseNotFound($this->provider);
			$response->setBody(json_encode(array("message" => "User not found")));
			$this->provider->get('application')->setResponse($response);
		}
		else
		{
			$response = new Application\WebResponseOk($this->provider);
			$response->setBody(json_encode($member));
			$this->provider->get('application')->setResponse($response);
		}
	}
}