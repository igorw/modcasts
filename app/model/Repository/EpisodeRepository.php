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
			->select('e, a, l')
			->join('e.theme_artist', 'a')
			->join('e.theme_license', 'l')
			->orderBy('e.id', 'DESC');
		$q = $qb->getQuery();
		return $q->execute();
	}
}
