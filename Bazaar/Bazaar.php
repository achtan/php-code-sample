<?php

namespace Inzeraz\Bazaar;


use Inzeraz\LeanMapper\Entity;
use Nette;


/**
 * @property string $status m:enum(self::STATUS_*)
 * @property string $name
 * @property string $slug
 * @property string $sort = 0
 * @property string $namespace
 * @property string $url
 * @property string|null $logo
 * @property string $email
 * @property string|null $termsOfUseUrl
 * @property string $appId
 * @property string $appSecret
 * @property string $apiSendAdUrl
 * @property string $apiDeleteAdUrl
 * @property string $apiAdInfoUrl
 * @property \Inzeraz\Authentication\User $manager m:belongsToOne
 */
class Bazaar extends Entity
{

	const STATUS_DRAFT = 'draft';
	const STATUS_LIVE = 'live';

}
