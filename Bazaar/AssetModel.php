<?php

namespace Inzeraz\Bazaar;


use Inzeraz\Bazaar\Asset;
use Inzeraz\Bazaar\AssetCriteria;
use Inzeraz\Bazaar\AssetRepository;
use Inzeraz\LeanMapper\QueryFactory;
use Inzeraz\TModelFind;
use Inzeraz\TModelFindAll;
use Nette;

class AssetModel
{

	use TModelFind;
	use TModelFindAll;


	/**
	 * @var AssetRepository
	 */
	private $bazaarRepository;

	/**
	 * @var \Inzeraz\LeanMapper\QueryFactory
	 */
	private $queryFactory;


	public function __construct(AssetRepository $bazaarRepository, QueryFactory $queryFactory)
	{

		$this->bazaarRepository = $bazaarRepository;
		$this->queryFactory = $queryFactory;
	}


	/**
	 * @param $id
	 *
	 * @return \Inzeraz\Bazaar\Asset
	 */
	public function find($id)
	{
		return $this->_find($id, Asset::class);
	}


	/**
	 * @return \Inzeraz\Bazaar\Asset[]
	 */
	public function findAll()
	{
		return $this->_findAll(Asset::class);
	}


	/**
	 * @param AssetCriteria $criteria
	 *
	 * @return \Inzeraz\Bazaar\Asset[]
	 */
	public function findBy(AssetCriteria $criteria)
	{
		$query = $this->queryFactory->selectFrom(Asset::class, 'e');

		if($criteria->shouldJoinRuleTable()) {
			$query->leftJoin('e.rules', 'r');

			if($criteria->filterByRule()) {
				$rule = $criteria->getRule();
				if(is_null($rule))
					$query->where('r.id IS NULL');
				else
					$query->where('r.id = %i', $rule);
			}
		}

		if($criteria->filterByBazaar())
			$query->where('e.bazaar = %i', $criteria->getBazaar()->id);

		if($criteria->filterByType())
			$query->where('e.type = %s', $criteria->getType());

//		TODO cakam na https://github.com/Tharos/LeanQuery/issues/13
//		$query->orderBy('e.parent');
		$query->orderBy('e.name');

		if($criteria->getReturn() == $criteria::RETURN_ENTITIES) {
			return $query->getEntities();
		} else if($criteria->getReturn() == $criteria::RETURN_COUNT) {
			$count = 0;
			foreach($query->getResult('e') as $row)
				$count++;

			return $count;
		} else {
			$pairs = [];
			$parents = [];

			/** @var $entity Asset */
			foreach($query->getEntities() as $entity) {
                if($entity->hasChild()) continue;

				if($entity->parent) {
                    list($parent, $indention) = $this->indentChild($entity->parent, $entity->name);
                    $parentName = $parent->name;
					$pairs[$parentName][$entity->id] = $indention;
					$parents[$entity->parent->id] = $parentName;
				} else {
					$pairs[$entity->id] = $entity->name;
				}
			}


            foreach($pairs as &$pair) {
                if(is_array($pair)) {
                    asort($pair);
                }
            }

            ksort($pairs);

			foreach($parents as $parentId => $parentName)
				if(isset($pairs[$parentId]))
					unset($pairs[$parentId]);

			return $pairs;
		}

	}


    private function indentChild(Asset $entity, $indention = null)
    {
        $indention = $entity->name . ' » ' . $indention;
        if($entity->parent) {
            return $this->indentChild($entity->parent, $indention);
        } else {
            return [$entity, $indention];
        }
    }

}
