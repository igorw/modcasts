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
	protected $request;
	protected $env;
	
	public function __construct(Request $request, Environment $env) {
		$this->request = $request;
		$this->env = $env;
	}
	
	public function render($templateFile, $vars = array()) {
		$vars['basePath'] = $this->request->getBasePath() . '/';
		
		$template = $this->env->twig->loadTemplate($templateFile);
		$output = $template->render($vars);
		return new Response($output);
	}
}
