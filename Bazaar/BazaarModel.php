<?php

namespace Inzeraz\Bazaar;


use Inzeraz\LeanMapper\QueryFactory;
use Inzeraz\TModelFind;
use Inzeraz\TModelFindAll;
use Inzeraz\TModelGetPairs;
use Nette;

class BazaarModel
{

	use TModelFind;

	/**
	 * @var BazaarRepository
	 */
	private $bazaarRepository;

	/**
	 * @var \Inzeraz\LeanMapper\QueryFactory
	 */
	private $queryFactory;


	public function __construct(BazaarRepository $bazaarRepository, QueryFactory $queryFactory)
	{
		$this->bazaarRepository = $bazaarRepository;
		$this->queryFactory = $queryFactory;
	}


	/**
	 * @param $id
	 *
	 * @return \Inzeraz\Bazaar\Bazaar
	 */
	public function find($id)
	{
		return $this->_find($id, Bazaar::class);
	}


	/**
	 * @param BazaarCriteria $criteria
	 *
	 * @return array|\Inzeraz\Bazaar\Bazaar[]
	 */
	public function findBy(BazaarCriteria $criteria)
	{
		$query = $this->queryFactory->selectFrom(Bazaar::class, 'e');

		if($criteria->filterByStatus()) {
			$query->where('e.status = %s', $criteria->getStatus());
		}

		$query->orderBy('e.sort');

		if($criteria->getReturn() == $criteria::RETURN_PAIRS) {
			$pairs = [];
			foreach($query->getEntities() as $bazaar)
				$pairs[$bazaar->id] = $bazaar->name;

			return $pairs;
		} else {
			return $query->getEntities();
		}
	}

	/**
	 * @param $slug
	 *
	 * @return \Inzeraz\Bazaar\Bazaar|null
	 */
	public function findBySlug($slug)
	{
		return $this->queryFactory->selectFrom(Bazaar::class, 'e')->where('e.slug = ?', $slug)->getEntity();
	}

}
