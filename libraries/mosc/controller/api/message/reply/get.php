<?php

namespace MOSC\Controller\API;

use Grisgris\Controller\Base;
use Grisgris\Application;

use MOSC\Member\Member;
use MongoId;
use UUID;

class MessageReplyGet extends Base
{
	public function execute()
	{
		$response = new Application\WebResponseOk($this->provider);
		$results = array();

		$filter = array("parent_id" => $this->input->get('parent_id'));

		foreach ($this->provider->get('db')->messages->find($filter) as $object)
		{
			$results[] = Member::formatMemberFromMongo($object);
		}
		$response->setBody(json_encode($results));
		$this->provider->get('application')->setResponse($response);
	}	
}