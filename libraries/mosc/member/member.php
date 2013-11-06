<?php

namespace MOSC\Member;

use Grisgris\Provider\Provider;

class Member
{
	private $provider;

	public function __construct(Provider $provider)
	{
		$this->provider = $provider;
	}

	public function get($id)
	{
		$collection = $this->provider->get("db")->members;
		$member = $collection->findOne(array("username" => $this->input->get("id")));
		$member = $collection->findOne(array("_id" => new MongoId($this->input->get("id"))));

		if ($member == null)
		{
			return $member;
		}

		$member = Member::formatMemberFromMongo($member);
		return $member;
	}

	public function create($data)
	{
		$message = array(
			"operation" => "create",
			"collection" => "members",
			"data" => $data
		);

		$bytes = $this->provider->get('kafka')->send(array(json_encode($message)), $this->provider->get('app.config')->kafka->topic);

		return $bytes > 0;
	}

	public function update($id, $data)
	{
		$message = array(
			"operation" => "update",
			"collection" => "members",
			"id" => $id,
			"data" => $data
		);

		$bytes = $this->provider->get('kafka')->send(array(json_encode($message)), $this->provider->get('app.config')->kafka->topic);

		return $bytes > 0;
	}

	public static function formatMemberFromMongo($member)
	{
		// Do a minor clean up to remove the Mongo OID field (_id).
		$member['id'] = (string) $member['_id'];
		unset($member['_id']);
		return $member;
	}
}