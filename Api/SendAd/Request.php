<?php
/**
 * This file is part of the inzeraz.
 * User: macbook
 * Created at: 10/07/14 07:42
 */

namespace Inzeraz\Api\SendAd;

use Inzeraz\Ad\PhotoFileStorage;
use Inzeraz\Api;
use Inzeraz\Api\AdInfo;
use Inzeraz\Ad\Ad;
use Inzeraz\FileManagement\ImagePipe;
use Nette;

class Request extends Api\Request
{

	/**
	 * @var \Inzeraz\Ad\Ad
	 */
	private $ad;

	/**
	 * @var ImagePipe
	 */
	private $imagePipe;


	public function __construct(Ad $ad, ImagePipe $imagePipe)
	{
		$this->ad = $ad;
		$this->imagePipe = $imagePipe;
	}


	public function getActionName()
	{
		return 'save-ad';
	}


	public function getData()
	{
		$ad = $this->ad;
		$user = $this->ad->user;

		$paths = $ad->photos;
		foreach($paths as &$path) {
			$path = $this->imagePipe->getPublicPath($path, \Inzeraz\Ad\PhotoFileStorage::LARGE);
		}

		return [
			'inzerazId' => $ad->id,
			'title' => htmlspecialchars($ad->title),
			//'!title' => $ad->title,
			'description' => $ad->descriptionSource,
			'!description' => $ad->description,
			'category' => $ad->category ? $ad->category->id : NULL,
			'type' => $ad->type ? $ad->type->id : NULL,
			'condition' => $ad->condition ? $ad->condition->id : NULL,
			'priceType' => $ad->priceType ? $ad->priceType->id : NULL,
			'price' => $ad->price,
			'locality' => $ad->locality ? $ad->locality->id : NULL,
			'images' => array_values($paths),
			'inzerazUserId' => $user->id,
			'userName' => $user->name,
			'userEmail' => $user->email,
			'userPhone' => $user->phone,
		];
	}


	public function verifyResponse(\Inzeraz\Api\Response $response)
	{
		/** @var $response \Inzeraz\Api\AdInfo\Response */
		$id = $response->getInfo() ? $response->getInfo()->getInzerazId() : NULL;

		if(!$id or $id != $this->ad->id) {
			$response->setError(\Inzeraz\Api\Response::ERROR_INVALID_AD_ID);
		}
	}
}


interface IRequestFactory
{

	/**
	 * @param Ad $ad
	 *
	 * @return Request
	 */
	public function create(Ad $ad);
}
