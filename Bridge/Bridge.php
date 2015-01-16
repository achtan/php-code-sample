<?php

namespace Inzeraz\Bridge;


use Inzeraz\Api\SendAd;
use Inzeraz\Api\AdInfo;
use Inzeraz\Api\AdListInfo;
use Inzeraz\Api\DeactivateAd;
use Inzeraz\Api\BadResponse;
use Inzeraz\Api\Crypt;
use Inzeraz\Api\Request;
use Inzeraz\Bazaar\Asset;
use Inzeraz\Bazaar\Bazaar;
use Inzeraz\LeanMapper\QueryFactory;
use Inzeraz\Mapping\Rule;
use Inzeraz\Mapping\RuleCriteria;
use Inzeraz\Mapping\RuleModel;
use Kdyby\Curl\CurlSender;
use Nette;

class Bridge
{

	/**
	 * @var Bazaar
	 */
	private $bazaar;

	/**
	 * @var \Inzeraz\LeanMapper\QueryFactory
	 */
	private $queryFactory;


	public function __construct(Bazaar $bazaar, QueryFactory $queryFactory)
	{
		$this->bazaar = $bazaar;
		$this->queryFactory = $queryFactory;
	}


	/**
	 * @param Request $request
	 *
	 * @return \Inzeraz\Api\AdInfo\Response|\Inzeraz\Api\AdListInfo\Response|\Inzeraz\Api\BadResponse
	 * @throws \Nette\NotImplementedException
	 */
	public function processRequest(Request $request)
	{

		if($request instanceof SendAd\Request) {
			$url = $this->bazaar->apiSendAdUrl;
		} else if($request instanceof AdListInfo\Request){
			$url = $this->bazaar->apiAdInfoUrl;
		} else if($request instanceof \Inzeraz\Api\DeleteAd\Request){
			$url = $this->bazaar->apiDeleteAdUrl;
		} else {
			throw new Nette\NotImplementedException;
		}

		$data = $this->mapData($request);

		$appId = $this->bazaar->appId;
		$appSecret = $this->bazaar->appSecret;
//		$crypt = Crypt::encrypt($data, $appSecret);
		$token = Crypt::getToken($data, $appId, $appSecret);

		$getData = ['token' => $token, 'action' => $request->getActionName()];
		$curlRequest = new \Kdyby\Curl\Request($url);
		$curlRequest->setMethod($curlRequest::POST);
		$curlRequest->getUrl()->appendQuery($getData);
		$curlRequest->setPost(['data' => $data]);


		$sender = new CurlSender();
		$sender->setFollowRedirects(TRUE);
		$curlResponse = $sender->send($curlRequest);

		$response = $this->processResponse($request, $curlResponse);

		return $response;
	}


	/**
	 * @param \Inzeraz\Api\Request $request
	 * @param \Kdyby\Curl\Response $curlResponse
	 *
	 * @return \Inzeraz\Api\AdInfo\Response|\Inzeraz\Api\AdListInfo\Response|\Inzeraz\Api\BadResponse
	 * @throws \Inzeraz\Api\BadResponseException
	 * @throws \Nette\NotImplementedException
	 */
	private function processResponse(Request $request, \Kdyby\Curl\Response $curlResponse)
	{
		if(!$curlResponse->isOk())
			return new BadResponse([], 991);

		$json = $curlResponse->getResponse();
		$bom = pack('H*','EFBBBF');
		$json = preg_replace("/^$bom/", '', $json);
		try {
			$responseData = Nette\Utils\Json::decode($json, TRUE);
		} catch(\Nette\Utils\JsonException $e) {
			return new BadResponse($json, BadResponse::ERROR_INVALID_JSON);
		}

		if(!Crypt::checkToken($responseData['token'], $responseData['data'], $this->bazaar->appId, $this->bazaar->appSecret))
			return new BadResponse($json, BadResponse::ERROR_INVALID_TOKEN);

		if($request instanceof SendAd\Request) {
			$response = new AdInfo\Response($responseData['data'], $responseData['error']);
		} else if($request instanceof AdListInfo\Request or $request instanceof \Inzeraz\Api\DeleteAd\Request) {
			$response = new AdListInfo\Response($responseData['data'], $responseData['error']);
		} else {
			throw new Nette\NotImplementedException;
		}

		$request->verifyResponse($response);
		return $response;
	}


	protected function mapData(Request $request)
	{
		if($request instanceof SendAd\Request) {
			$data = $request->getData();

			$list = [
				Asset::TYPE_AD_CATEGORY => 'category',
				Asset::TYPE_AD_TYPE => 'type',
				Asset::TYPE_AD_CONDITION => 'condition',
				Asset::TYPE_AD_PRICE_TYPE => 'priceType',
				Asset::TYPE_LOCALITY => 'locality',
			];

			$orString = [];
			$or = [];
			foreach($list as $key => $value) {
				if($data[$value]) {
					$orString[] = 'e.' . $key . ' = %i';
					$or[] = $data[$value];
				}
				$data['inzerazData'][$value] = $data[$value];
			}

			$query = $this->queryFactory->selectFrom(Rule::class, 'e');
			$query->where('e.asset IS NOT NULL AND e.bazaar = %i', $this->bazaar->id);

			call_user_func_array([$query, 'where'], array_merge(['(' . implode(' OR ', $orString) . ')'], $or));

			/** @var $rule \Inzeraz\Mapping\Rule */
			foreach($query->getEntities() as $rule) {
				$asset = $rule->asset;
				$key = $list[$asset->type];
				$data[$key] = $asset->mappedTo;
			}

			$data = Nette\Utils\Json::encode($data);
		} else {
			$data = $request->getJson();
		}

		return $data;
	}
}
