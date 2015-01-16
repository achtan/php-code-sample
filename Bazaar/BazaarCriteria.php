<?php
/**
 * This file is part of the inzeraz.
 * User: macbook
 * Created at: 25/07/14 09:32
 */

namespace Inzeraz\Bazaar;


use Nette;

class BazaarCriteria
{

	const RETURN_ENTITIES = 'entities';
	const RETURN_PAIRS = 'pairs';

	/**
	 * @var string
	 */
	protected $status;


	/**
	 * @var string
	 */
	protected $return = self::RETURN_ENTITIES;


	public function getStatus()
	{
		return $this->status;
	}


	public function filterByStatus()
	{
		return isset($this->status);
	}


	public function status($status)
	{
		$this->status = $status;

		return $this;
	}


	public function getReturn()
	{
		return $this->return;
	}


	public function returnPairs()
	{
		$this->return = self::RETURN_PAIRS;

		return $this;
	}
}
