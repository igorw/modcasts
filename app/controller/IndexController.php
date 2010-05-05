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
	
	public function feedAction() {
		$repository = $this->container->em->getRepository('Modcasts\Entities\Episode');
		$episodes = $repository->findAllDesc();
		
		$feed = new \Modcasts\Feed\Feed;
		$feed->title = 'modcasts';
		$feed->link = $this->request->getScheme() . '://' .
			$this->request->getHttpHost() . $this->request->getBaseUrl();
		$feed->updated = $repository->getLatestUpdatedTime();
		$feed->authors[] = new \Modcasts\Feed\Author('Igor Wiedler');
		
		foreach ($episodes as $episode) {
			$item = new \Modcasts\Feed\FeedItem;
			$item->id = $episode->id;
			$item->title = $episode->title;
			$item->link = $this->request->getScheme() . '://' .
				$this->request->getHttpHost() . $this->request->getBaseUrl() .
				'index/episode/' . $episode->id;
			$item->updated = $episode->updated;
			$item->summary = $episode->show_notes;
			
			$feed->entries[] = $item;
		}
		
		$writer = new \Modcasts\Feed\Writer\Atom($feed);
		
		$response = new Response;
		$response->setHeader('Content-Type', $writer->getContentType());
		$response->setContent($writer->dump());
		return $response;
	}
}
