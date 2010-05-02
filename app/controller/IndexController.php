<?php
/**
 * This file is part of modcasts.
 *
 * (c) 2010 Igor Wiedler
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Modcasts\Controller;

use Modcasts\Controller;

use Symfony\Components\RequestHandler\Request,
	Symfony\Components\RequestHandler\Response;

class IndexController extends Controller {
	public function indexAction(Request $request) {
		$q = $this->env->em->createQuery(
			'select e, l, a
			from Modcasts\Entities\Episode e
			join e.theme_license l
			join e.theme_artist a');
		
		$episodes = $q->execute();
		
		return $this->render('index.html', array(
			'episodes'	=> $episodes,
		));
	}
}
