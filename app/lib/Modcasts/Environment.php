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

use Symfony\Components\DependencyInjection\ContainerInterface;

class Environment {
	public $container;
	public $twig;
	public $em;
	
	public function __construct(ContainerInterface $container) {
		$this->container = $container;
		$this->twig = $this->container->twig;
		$this->em = $this->container->em;
	}
}
