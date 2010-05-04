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

class LicenseRepository extends EntityRepository {
	public function findAll() {
		$qb = $this->createQueryBuilder('l')
			->orderBy('l.name', 'ASC');
		$q = $qb->getQuery();
		return $q->execute();
	}
}
