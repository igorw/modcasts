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

class ErrorController extends Controller {
	private $exception;
	
	public function __construct(Request $request, Environment $env, \Exception $exception = null) {
		parent::__construct($request, $env);
		$this->exception = $exception;
	}
	
	public function exceptionAction() {
		return $this->render('error/exception.html', array(
			'exception'	=> $this->exception,
		));
	}
	
	public function fileNotFoundAction() {
		return $this->render('error/404.html');
	}
}
