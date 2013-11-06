<?php

namespace MOSC\App;

use Grisgris\Application\Web;
use Grisgris\Router;
use Grisgris\Input;
//use Exception;

use Mongo;
use MongoDB;
use Kafka_Producer;

class API extends Web
{
	public function doExecute()
	{
		try
		{
			// Wire up the configuration file.
			$this->provider->set('app.config', json_decode(file_get_contents(APP_BASE . '/config/app.json')));

			// Set up the MongoDB client.
			$client = new Mongo($this->provider->get('app.config')->mongo->host);
			$db = new MongoDB($client, $this->provider->get('app.config')->mongo->database);
			$this->provider->set('db', $db);

			// Set up the Kafka queue.
			$kafkaConfig = $this->provider->get('app.config')->kafka;
			$producer = new Kafka_Producer($kafkaConfig->host, $kafkaConfig->port);
			$this->provider->set('kafka', $producer);

			// Set up the router!
			$router = new Router\REST($this->provider);
			$router->addMaps(json_decode(file_get_contents(APP_BASE . '/config/api.json')));
			$router->addMaps(json_decode(file_get_contents(APP_BASE . '/config/web.json')));
			$router->setControllerNamespace('\MOSC\Controller\API');
			$router->setDefaultController('Main');
			$controller = $router->getController($this->provider->get('uri.route'));
			$controller->execute();
		}
		catch (Exception $e)
		{
			die('{"message":"Error: There was an error processing this request."}');
		}
	}

	public function fetchConfigurationData()
	{
		return array();
	}

	/**
	 * Fetch an input object.
	 * Override in this case to return an Input\Json class.
	 */
	public function fetchInput()
	{
		$input = $this->provider->get('input');
		if ($input instanceof Input)
		{
			return $input;
		}

		return new Input\Json($this->provider);
	}
}