<?php

namespace Inzeraz\Api;

use Nette\Utils\Json;

abstract class Request
{

	abstract public function getData();
	abstract public function getActionName();
	abstract public function verifyResponse(Response $response);


	/**
	 * @return string
	 */
	public function getJson()
	{
		return json_encode($this->getData());
	}


}
