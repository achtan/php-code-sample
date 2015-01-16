<?php
/**
 * This file is part of the inzeraz.
 * User: macbook
 * Created at: 11/07/14 16:24
 */

namespace Inzeraz\Api\AdInfo;


use Inzeraz\Ad\Published;
use Nette;

class AdInfo
{

	/**
	 * @var array
	 */
	private $data;

	private $ok = TRUE;


	/**
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$this->data = $this->validateAndFilterData($data);
	}

	/**
	 * @return bool
	 */
	public function isOk()
	{
		return $this->ok;
	}

	/**
	 * @return bool
	 */
	public function adExists()
	{
		return $this->getStatus() != Published::AD_STATUS_NOT_EXISTS;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->data['id'];
	}

	/**
	 * @return int
	 */
	public function getInzerazId()
	{
		return $this->data['inzerazId'];
	}

	/**
	 * @return int
	 */
	public function getViewsCount()
	{
		return $this->data['viewsCount'];
	}

	public function getUrl()
	{
		return $this->data['url'];
	}

	public function getStatus()
	{
		return $this->data['status'];
	}


	public function getExpireAt()
	{
		return $this->data['expireAt'];
	}


	public function isFeatured()
	{
		return $this->data['featured'];
	}


	/**
	 * @param array $data
	 *
	 * @return array
	 */
	protected function validateAndFilterData(array $data)
	{
		if(isset($data['type']) and $data['type'] == 'ad-info' and
			isset($data['status']) and
			isset($data['inzerazId']) and is_numeric($data['inzerazId'])) {

			if($data['status'] == Published::AD_STATUS_NOT_EXISTS)
			{
				return $data;
			} else if (
				isset($data['id']) and is_numeric($data['id']) and
				isset($data['url']) and
				isset($data['status']) and
				array_key_exists('expire', $data) and (is_numeric($data['expire']) or is_null($data['expire'])) and
				array_key_exists('featured', $data) and
				array_key_exists('viewsCount', $data) and (is_numeric($data['viewsCount']) or is_null($data['viewsCount'])))
			{
				if(is_numeric($data['expire']))
					$data['expireAt'] = Nette\Utils\DateTime::from($data['expire']);
				else
					$data['expireAt'] = NULL;

				return $data;
			}

		}

		return [];
	}

}
