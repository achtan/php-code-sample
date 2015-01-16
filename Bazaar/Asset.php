<?php

namespace Inzeraz\Bazaar;


use Inzeraz\LeanMapper\Entity;
use Nette;


/**
 * @property \Inzeraz\Bazaar\Bazaar $bazaar m:hasOne
 * @property string $type m:enum(self::TYPE_*)
 * @property string $mappedTo
 * @property \Inzeraz\Bazaar\Asset|null $parent m:hasOne(parent_id)
 * @property \Inzeraz\Bazaar\Asset[] $children m:belongsToMany(parent_id)
 * @property \Inzeraz\Mapping\Rule[] $rules m:belongsToMany(asset_id)
 * @property string $name
 */
class Asset extends Entity
{

	const TYPE_AD_CATEGORY = 'adCategory';
	const TYPE_AD_TYPE = 'adType';
	const TYPE_AD_CONDITION = 'adCondition';
	const TYPE_AD_PRICE_TYPE = 'adPriceType';
	const TYPE_LOCALITY = 'locality';

    public function hasChild()
    {
        return (bool) count($this->children);
    }
}
