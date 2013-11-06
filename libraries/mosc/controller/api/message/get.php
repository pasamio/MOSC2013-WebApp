<?php

namespace MOSC\Controller\API;

use Grisgris\Controller\Base;
use Grisgris\Application;

use MOSC\Member\Member;
use MongoId;

class MessageGet extends Base
{
	public function execute()
	{
		$response = new Application\WebResponseOk($this->provider);
		$results = array();

		$filter = array("parent_id" => "");
		if ($this->input->get('id'))
		{
			// $filter["_id"] = new MongoId($this->input->get('id'));
			$filter["_id"] = $this->input->get('id');
		}

		foreach ($this->provider->get('db')->messages->find($filter) as $object)
		{
			$object = Member::formatMemberFromMongo($object);
			$subfilter = array("parent_id" => $object['id']);
			$object['replies'] = array();
			foreach ($this->provider->get('db')->messages->find($subfilter) as $subobject)
			{
				$object['replies'][] = Member::formatMemberFromMongo($subobject);
			}
			$results[] = $object;
		}

		if (isset($filter['_id']))
		{
			$results = $results[0];
		}
	
		$response->setBody(json_encode($results));
		$this->provider->get('application')->setResponse($response);
	}	
}