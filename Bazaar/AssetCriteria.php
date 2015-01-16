<?php
/**
 * This file is part of the inzeraz.
 * User: macbook
 * Created at: 18/07/14 08:19
 */

namespace Inzeraz\Bazaar;


use Nette;

class AssetCriteria
{

	const RETURN_ENTITIES = 'entities';
	const RETURN_PAIRS = 'pairs';
	const RETURN_COUNT = 'count';

	/**
	 * @var null|Bazaar
	 */
	protected $bazaar;


	/**
	 * @var string
	 */
	protected $type;


	/**
	 * @var bool
	 */
	protected $joinRuleTable;

	/**
	 * @var bool|int|null
	 */
	protected $rule = FALSE;

	/**
	 * @var string
	 */
	protected $return = self::RETURN_ENTITIES;


	/**
	 * @return \Inzeraz\Bazaar\Bazaar|null
	 */
	public function getBazaar()
	{
		return $this->bazaar;
	}


	/**
	 * @return bool
	 */
	public function filterByBazaar()
	{
		return isset($this->bazaar);
	}


	/**
	 * @param \Inzeraz\Bazaar\Bazaar|null $bazaar
	 *
	 * @return $this
	 */
	public function bazaar(Bazaar $bazaar)
	{
		$this->bazaar = $bazaar;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getReturn()
	{
		return $this->return;
	}

	/**
	 * @return $this
	 */
	public function returnPairs()
	{
		$this->return = self::RETURN_PAIRS;

		return $this;
	}


	public function returnCount()
	{
		$this->return = self::RETURN_COUNT;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * @return bool
	 */
	public function filterByType()
	{
		return isset($this->type);
	}


	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function type($type)
	{
		$this->type = $type;

		return $this;
	}


	public function shouldJoinRuleTable()
	{
		return isset($this->joinRuleTable);
	}

	/**
	 * @return bool|int|null
	 */
	public function getRule()
	{
		return $this->rule;
	}


	public function filterByRule()
	{
		return isset($this->rule) or is_null($this->rule);
	}


	public function rule($ruleId)
	{
		$this->joinRuleTable = true;
		$this->rule = $ruleId;

		return $this;
	}

	public function ruleIsNull()
	{
		return $this->rule(NULL);
	}
}
