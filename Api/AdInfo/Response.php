<?php
/**
 * This file is part of the inzeraz.
 * User: macbook
 * Created at: 10/07/14 13:26
 */

namespace Inzeraz\Api\AdInfo;


use Inzeraz\Api;
use Nette;

class Response extends Api\Response
{

	/**
	 * @var AdInfo|null
	 */
	protected $info;


	/**
	 * @param array $data
	 */
	protected function init(array $data)
	{
		$info = new AdInfo($data);

		if($info->isOk()) {
			$this->info = $info;
		} else {
			$this->error = self::ERROR_INVALID_DATA;
		}
	}

	/**
	 * @return AdInfo|null
	 */
	public function getInfo()
	{
		return $this->info;
	}
}
