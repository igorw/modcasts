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
		$repository = $this->container->em->getRepository('Modcasts\Entities\Episode');
		$episodes = $repository->findAllDesc();
		
		return $this->render('index.html', array(
			'episodes'	=> $episodes,
		));
	}
	
	public function episodeAction($id) {
		$episode = $this->container->em->find('Modcasts\Entities\Episode', $id);
		
		if ( ! $episode) {
			throw new \Modcasts\FileNotFoundException;
		}
		
		return $this->render('episode.html', array(
			'episode'	=> $episode,
		));
	}
}
