<?php
/**
 * This file is part of modcasts.
 *
 * (c) 2010 Igor Wiedler
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Modcasts\Entities\Repository;

use Doctrine\ORM\EntityRepository;

class EpisodeRepository extends EntityRepository {
	public function findAllDesc() {
		$qb = $this->createQueryBuilder('e')
			->orderBy('e.id', 'DESC');
		$q = $qb->getQuery();
		return $q->execute();
	}
	
	public function getLatestUpdatedTime() {
		$qb = $this->createQueryBuilder('e')
			->select('partial e.{id,updated}')
			->orderBy('e.updated', 'DESC');
		$q = $qb->getQuery();
		$episode = array_shift($q->setMaxResults(1)->execute());
		return $episode->updated;
	}
	
	public function getNextId() {
		$qb = $this->createQueryBuilder('e')
			->select('partial e.{id}')
			->orderBy('e.id', 'DESC');
		$q = $qb->getQuery();
		$episode = array_shift($q->setMaxResults(1)->execute());
		
		if ($episode === null) {
			return 0;
		}
		
		return $episode->id + 1;
	}
}
