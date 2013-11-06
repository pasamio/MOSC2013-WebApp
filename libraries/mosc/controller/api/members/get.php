<?php

namespace MOSC\Controller\API;

use Grisgris\Controller\Base;
use Grisgris\Application;

use MOSC\Member\Member;

class MembersGet extends Base
{
	public function execute()
	{
		$response = new Application\WebResponseOk($this->provider);
		$results = array();
		foreach ($this->provider->get('db')->members->find() as $object)
		{
			$results[] = Member::formatMemberFromMongo($object);
		}
		$response->setBody(json_encode($results));
		$this->provider->get('application')->setResponse($response);
	}
}