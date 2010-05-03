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

use Modcasts\Controller,
	Modcasts\Environment;

use Symfony\Components\RequestHandler\Request,
	Symfony\Components\RequestHandler\Response;

class BackendController extends Controller {
	private $session;
	
	public function __construct(Request $request, Environment $env) {
		parent::__construct($request, $env);
		$this->session = $this->env->getSession();
	}
	
	public function indexAction() {
		$this->loginCheckAndRedirect();
		
		return $this->render('backend/index.html');
	}
	
	public function listEpisodesAction() {
		$this->loginCheckAndRedirect();
		
		$q = $this->env->em->createQuery(
			'select e, a, l
			from Modcasts\Entities\Episode e
			join e.theme_artist a
			join e.theme_license l
			order by e.id desc');
		$episodes = $q->execute();
		
		return $this->render('backend/listEpisodes.html', array(
			'episodes'	=> $episodes,
		));
	}
	
	public function editEpisodeAction($id) {
		$this->loginCheckAndRedirect();
		
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
		
		$artists = $this->findAllArtists();
		$licenses = $this->findAllLicenses();
		
		$errors = array();
		
		if ($this->request->get('submit')) {
			$errors = $this->processCheckAndPersistEpisode($episode);
		}
		
		return $this->render('backend/editEpisode.html', array(
			'formAction'		=> $this->request->getBasePath() . '/backend/editEpisode/' . $episode->id,
			'episode'		=> $episode,
			'artists'		=> $artists,
			'licenses'		=> $licenses,
			'errors'		=> $errors,
		));
	}
	
	public function newEpisodeAction() {
		$this->loginCheckAndRedirect();
		
		$episode = new \Modcasts\Entities\Episode;
		$episode->id = $this->nextEpisodeId();
		
		$artists = $this->findAllArtists();
		$licenses = $this->findAllLicenses();
		
		$errors = array();
		
		if ($this->request->get('submit')) {
			$errors = $this->processCheckAndPersistEpisode($episode);
		}
		
		return $this->render('backend/newEpisode.html', array(
			'formAction'		=> $this->request->getBasePath() . '/backend/newEpisode',
			'displayIdField'	=> true,
			'episode'		=> $episode,
			'artists'		=> $artists,
			'licenses'		=> $licenses,
			'errors'		=> $errors,
		));
	}
	
	public function nextEpisodeId() {
		$q = $this->env->em->createQuery(
			'select partial e.{id}
			from Modcasts\Entities\Episode e
			order by e.id desc');
		$episode = array_shift($q->setMaxResults(1)->execute());
		
		if ($episode === null) {
			return 0;
		}
		
		return $episode->id + 1;
	}
	
	public function processCheckAndPersistEpisode($episode) {
		$episode->title = (string) $this->request->get('title');
		$episode->show_notes = (string) $this->request->get('show_notes');
		$episode->theme_artist = $this->findArtist((int) $this->request->get('theme_artist'));
		$episode->theme_license = $this->findLicense((int) $this->request->get('theme_license'));
		
		if ($file_bytes = (int) $this->request->get('file_bytes')) {
			$episode->file_bytes = $file_bytes;
		}
		
		$errors = $episode->validate();
		
		if ( ! sizeof($errors)) {
			$this->env->em->persist($episode);
			$this->env->em->flush();
			throw new \Modcasts\RedirectException('backend/listEpisodes');
		}
		
		return $errors;
	}
	
	public function loginAction() {
		if ($this->isLoggedIn()) {
			throw new \Exception("You are already logged in.");
		}
		
		$username = '';
		$error = false;
		
		if ($this->request->get('submit')) {
			$username = (string) $this->request->get('username');
			$password = (string) $this->request->get('password');

			if ($account = $this->authenticate($username, $password)) {
				$this->session->write('account_id', $account->id);
				throw new \Modcasts\RedirectException('backend');
			}

			$error = true;
		}
		
		return $this->render('backend/login.html', array(
			'username'	=> $username,
			'error'		=> $error,
		));
	}
	
	public function authenticate($username, $password) {
		$q = $this->env->em->createQuery(
			'select a
			from Modcasts\Entities\Account a
			where a.username = ?1
			and a.password = ?2');
		$account = array_shift($q->execute(array(
			1	=> $username,
			2	=> hash('sha256', $password),
		)));
		
		return $account;
	}
	
	public function loginCheckAndRedirect() {
		if ( ! $this->isLoggedIn()) {
			throw new \Modcasts\RedirectException('backend/login');
		}
	}
	
	public function isLoggedIn() {
		return $this->session->read('account_id');
	}
	
	public function findAllArtists() {
		$q = $this->env->em->createQuery(
			'select a
			from Modcasts\Entities\Artist a
			order by a.name');
		$artists = $q->execute();
		return $artists;
	}
	
	public function findAllLicenses() {
		$q = $this->env->em->createQuery(
			'select l
			from Modcasts\Entities\License l
			order by l.name');
		$artists = $q->execute();
		return $artists;
	}
	
	public function findArtist($id) {
		$q = $this->env->em->createQuery(
			'select a
			from Modcasts\Entities\Artist a
			where a.id = ?1');
		$artist = array_shift($q->execute(array(1 => $id)));
		return $artist;
	}
	
	public function findLicense($id) {
		$q = $this->env->em->createQuery(
			'select l
			from Modcasts\Entities\License l
			where l.id = ?1');
		$license = array_shift($q->execute(array(1 => $id)));
		return $license;
	}
}
