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
	
	public function __construct($basePath, \Twig_Environment $twig, $em) {
		$this->basePath = (substr($basePath, -1) == '/') ? $basePath : $basePath . '/';
		$this->twig = $twig;
		$this->em = $em;
	}
}
