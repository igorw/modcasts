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
	public function indexAction() {
		$q = $this->env->em->createQuery(
			'select e, a, l
			from Modcasts\Entities\Episode e
			join e.theme_artist a
			join e.theme_license l
			order by e.id desc');
		$episodes = $q->execute();
		
		return $this->render('index.html', array(
			'episodes'	=> $episodes,
		));
	}
	
	public function episodeAction($id) {
		$q = $this->env->em->createQuery(
			'select e, a, l
			from Modcasts\Entities\Episode e
			join e.theme_artist a
			join e.theme_license l
			where e.id = ?1');
		$episode = array_shift($q->execute(array(1 => $id)));
		
		if ( ! $episode) {
			throw new \Modcasts\FileNotFoundException;
		}
		
		return $this->render('episode.html', array(
			'episode'	=> $episode,
		));
	}
}
