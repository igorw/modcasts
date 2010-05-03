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

class DevController extends Controller {
	public function populateAction() {
		$episode = new \Modcasts\Entities\Episode;
		$episode->id = 0;
		$episode->title = 'Introducing Slave';
		$episode->show_notes = "The Slave abuses HTTP to install phpBB for you. " . 
			"He is a command-line PHP script who makes HTTP requests though phpBB 3.0's installation.\n" . 
			"\n" . 
			"In this episode, we will take a look at Slave. " . 
			"I will show you how to use it, explain how it works and give you a little demo.\n";
		$episode->file_bytes = 173919;
		
		$episode->theme_artist = new \Modcasts\Entities\Artist;
		$episode->theme_artist->name = 'Latché Swing';
		$episode->theme_artist->website = 'http://freemusicarchive.org/music/Latch_Swing/';
		
		$episode->theme_license = new \Modcasts\Entities\License;
		$episode->theme_license->name = 'CC BY-NC-SA 2.0';
		$episode->theme_license->url = 'http://creativecommons.org/licenses/by-nc-sa/2.0/';
		
		$this->env->em->persist($episode->theme_artist);
		$this->env->em->flush();
		$this->env->em->persist($episode->theme_license);
		$this->env->em->flush();
		$this->env->em->persist($episode);
		$this->env->em->flush();
		
		return new Response("done");
	}
}
