<?php

namespace Inzeraz\Api\DeleteAd;

use Inzeraz\Api;
use Nette\NotImplementedException;

class Request extends Api\Request
{

	/**
	 * @var array
	 */
	private $adsIds;


	/**
	 * @param array $adsIds
	 */
	public function __construct(array $adsIds)
	{
		$this->adsIds = $adsIds;
	}


	/**
	 * @return string
	 */
	public function getActionName()
	{
		return 'delete-ad';
	}


	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->adsIds;
	}


	/**
	 * @param Api\Response $response
	 *
	 * @throws \Nette\NotImplementedException
	 */
	public function verifyResponse(\Inzeraz\Api\Response $response)
	{
		/** @var $response \Inzeraz\Api\AdListInfo\Response */
		$list = $response->getList();
		if (is_array($list)) {
			/** @var $info \Inzeraz\Api\AdInfo\AdInfo */
			foreach ($list as $info) {
				$id = $info->getInzerazId();
				if (!in_array($id, $this->adsIds)) {
					$response->setError(\Inzeraz\Api\Response::ERROR_INVALID_AD_ID);

					return;
				}
			}
		} else {
			throw new NotImplementedException('tu by som nemal byt...');
		}
	}
}


interface IRequestFactory
{

	/**
	 * @param array $adsIds
	 *
	 * @return Request
	 */
	public function create(array $adsIds);
}
