<?php

namespace MOSC\Controller\API;

use Grisgris\Controller\Base;
use Grisgris\Application;

class User extends Base
{
	public function execute()
	{
		switch ($this->input->getMethod())
		{
			// New User.
			case 'POST':

				break;
			case 'GET':

				break;
			case 'PUT':

				break;
			case 'DELETE':

				break;
		}
		var_dump($this->input->getMethod());
	}
}