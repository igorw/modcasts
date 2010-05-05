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
		$this->session = $this->env->container->session;
	}
	
	public function indexAction() {
		$this->loginCheckAndRedirect();
		
		return $this->render('backend/index.html');
	}
	
	public function listEpisodesAction() {
		$this->loginCheckAndRedirect();
		
		$repository = $this->env->em->getRepository('Modcasts\Entities\Episode');
		$episodes = $repository->findAllDesc();
		
		return $this->render('backend/listEpisodes.html', array(
			'episodes'		=> $episodes,
			'csrfTokenFactory'	=> $this->env->container->csrf_token_factory,
		));
	}
	
	public function editEpisodeAction($id) {
		$this->loginCheckAndRedirect();
		
		$episode = $this->env->em->find('Modcasts\Entities\Episode', $id);
		
		if ( ! $episode) {
			throw new \Modcasts\FileNotFoundException;
		}
		
		$artists = $this->env->em->getRepository('Modcasts\Entities\Artist')
			->findAll();
		$licenses = $this->env->em->getRepository('Modcasts\Entities\License')
			->findAll();
		
		$errors = array();
		
		$csrfTokenFactory = $this->env->container->csrf_token_factory;
		$csrfToken = $csrfTokenFactory->getToken('backend/editEpisode/' . $episode->id,
			$this->request->get('csrfCreated'));
		
		if ($this->request->get('submit')) {
			if ( ! $csrfToken->check($this->request->get('csrfHash'))) {
				$errors[] = 'Please resubmit the form';
			}
			
			$errors = array_merge($errors,
				$this->processCheckAndPersistEpisode($episode));
		}
		
		return $this->render('backend/editEpisode.html', array(
			'formAction'		=> $this->request->getBasePath() . '/backend/editEpisode/' . $episode->id,
			'episode'		=> $episode,
			'artists'		=> $artists,
			'licenses'		=> $licenses,
			'errors'		=> $errors,
			'csrfCreated'		=> $csrfToken->getCreated(),
			'csrfHash'		=> $csrfToken->getHash(),
		));
	}
	
	public function newEpisodeAction() {
		$this->loginCheckAndRedirect();
		
		$episode = new \Modcasts\Entities\Episode;
		$episode->id = $this->env->em->getRepository('Modcasts\Entities\Episode')
			->getNextId();
		
		$artists = $this->env->em->getRepository('Modcasts\Entities\Artist')
			->findAll();
		$licenses = $this->env->em->getRepository('Modcasts\Entities\License')
			->findAll();
		
		$errors = array();
		
		$csrfTokenFactory = $this->env->container->csrf_token_factory;
		$csrfToken = $csrfTokenFactory->getToken('backend/newEpisode',
			$this->request->get('csrfCreated'));
		
		if ($this->request->get('submit')) {
			if ( ! $csrfToken->check($this->request->get('csrfHash'))) {
				$errors[] = 'Please resubmit the form';
			}
			
			$errors = array_merge($errors,
				$this->processCheckAndPersistEpisode($episode));
		}
		
		return $this->render('backend/newEpisode.html', array(
			'formAction'		=> $this->request->getBasePath() . '/backend/newEpisode',
			'displayIdField'	=> true,
			'episode'		=> $episode,
			'artists'		=> $artists,
			'licenses'		=> $licenses,
			'errors'		=> $errors,
			'csrfCreated'		=> $csrfToken->getCreated(),
			'csrfHash'		=> $csrfToken->getHash(),
		));
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
	
	public function deleteEpisodeAction($id) {
		$this->loginCheckAndRedirect();
		
		$episode = $this->env->em->find('Modcasts\Entities\Episode', $id);
		
		if ( ! $episode) {
			throw new \Modcasts\FileNotFoundException;
		}
		
		$csrfTokenFactory = $this->env->container->csrf_token_factory;
		$csrfToken = $csrfTokenFactory->getToken('backend/deleteEpisode/' . $episode->id,
			$this->request->get('csrfCreated'));
		if ( ! $csrfToken->check($this->request->get('csrfHash'))) {
			throw new \Exception('Please try again.');
		}
		
		$this->env->em->remove($episode);
		$this->env->em->flush();
		throw new \Modcasts\RedirectException('backend/listEpisodes');
	}
	
	public function loginAction() {
		if ($this->isLoggedIn()) {
			throw new \Modcasts\RedirectException('backend');
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
		$repository = $this->env->em->getRepository('Modcasts\Entities\Account');
		$account = $repository->findOneBy(array(
			'username'	=> $username,
			'password'	=> hash('sha256', $password),
		));
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
	
	public function logoutAction() {
		if ( ! $this->isLoggedIn()) {
			throw new \Modcasts\RedirectException('');
		}
		
		$this->session->write('account_id', null);
		throw new \Modcasts\RedirectException('');
	}
	
	public function findArtist($id) {
		$artist = $this->env->em->find('Modcasts\Entities\Artist', $id);
		return $artist;
	}
	
	public function findLicense($id) {
		$license = $this->env->em->find('Modcasts\Entities\License', $id);
		return $license;
	}
}
