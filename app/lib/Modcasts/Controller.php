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

use Symfony\Components\RequestHandler\Request,
	Symfony\Components\RequestHandler\Response;

abstract class Controller {
	protected $env;
	
	public function __construct(Environment $env) {
		$this->env = $env;
	}
	
	public function render($templateFile, $vars) {
		$template = $this->env->twig->loadTemplate($templateFile);
		$output = $template->render($vars);
		return new Response($output);
	}
}
