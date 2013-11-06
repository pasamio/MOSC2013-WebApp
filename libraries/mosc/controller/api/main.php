<?php

namespace MOSC\Controller\API;

use Grisgris\Controller\Base;
use Grisgris\Application;

class Main extends Base
{
	public function execute()
	{
		$response = new Application\WebResponseOk($this->provider);
		$response->setBody(json_encode(array('message' => 'Hello, World!')));
		$this->provider->get('application')->setResponse($response);
	}
}