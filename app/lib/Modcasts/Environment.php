<?php
/**
 * This file is part of modcasts.
 *
 * (c) 2010 Igor Wiedler
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Modcasts;

class Environment {
	public $basePath;
	public $twig;
	public $em;
	public $sessionFactory;
	public $appConfig;
	
	public function __construct($basePath, \Twig_Environment $twig, $em, $sessionFactory, $appConfig) {
		$this->basePath = (substr($basePath, -1) == '/') ? $basePath : $basePath . '/';
		$this->twig = $twig;
		$this->em = $em;
		$this->sessionFactory = $sessionFactory;
		$this->appConfig = $appConfig;
	}
	
	public function getSession() {
		$factory = $this->sessionFactory;
		return $factory();
	}
	
	public function getCSRFToken($formName, $created = null) {
		return new CSRFToken($formName, $this->appConfig['csrfSecret'], $created);
	}
}
