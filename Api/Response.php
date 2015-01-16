<?php

namespace Inzeraz\Api;


use Nette\Utils\Json;

abstract class Response
{

	const ERROR_INVALID_DATA = 301;
	const ERROR_INVALID_AD_ID = 302;
	const ERROR_INVALID_JSON = 981;
	const ERROR_INVALID_TOKEN = 982;

	/**
	 * @var int
	 */
	protected $error;

	private $data;


	/**
	 * @param array $data
	 * @param $error
	 */
	public function __construct($data, $error)
	{
		$this->error = (int) $error;
		$this->data = $data;
		if(is_array($data)) {
			$this->init($data);
		}
	}


	/**
	 * @return bool
	 */
	public function isOk()
	{
		return !$this->error;
	}


	/**
	 * @return int
	 */
	public function getError()
	{
		return $this->error;
	}

	public function setError($error)
	{
		$this->error = $error;
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return is_array($this->data) ? Json::encode($this->data) : $this->data;
	}

	abstract protected function init(array $data);

}
