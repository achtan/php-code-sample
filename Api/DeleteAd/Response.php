<?php

namespace Inzeraz\Api\DeleteAd;


use Inzeraz\Api;
use Inzeraz\Api\AdInfo\AdInfo;
use Nette;

class Response extends Api\Response
{

	/**
	 * @var AdInfo[]|null
	 */
	protected $list;


	/**
	 * @return AdInfo[]|null
	 */
	public function getList()
	{
		return $this->list;
	}


	/**
	 * @param array $data
	 */
	protected function init(array $data)
	{
		$list = [];
		foreach($data as $value) {
			$info = new AdInfo($value);
			if($info->isOk()) {
				$list[] = $info;
			} else {
				$this->error = self::ERROR_INVALID_DATA;
				return;
			}
		}

		$this->list = $list;
	}
}
