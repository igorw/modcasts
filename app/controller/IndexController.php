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

use Modcasts\JsonEntityManager;

use Modcasts\Controller;

use Symfony\Components\RequestHandler\Request,
	Symfony\Components\RequestHandler\Response;

class IndexController extends Controller {
	public function indexAction(Request $request) {
		$em = new JsonEntityManager($this->env->basePath . 'data/episodes_meta');

		$episodes = $em->findAll();
		
		usort($episodes, function($a, $b) {
			// sort by date descending
			if ($a->created_at == $b->created_at) {
				return 0;
			}
			return ($a->created_at > $b->created_at) ? -1 : 1;
		});
		
		return $this->render('index.html', array(
			'episodes'	=> $episodes,
		));
	}
}
