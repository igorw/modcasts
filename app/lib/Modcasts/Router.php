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

use Symfony\Components\RequestHandler\Request;

class Router {
	private $request;
	private $env;
	
	public function __construct(Request $request, Environment $env) {
		$this->request = $request;
		$this->env = $env;
	}
	
	public function dispatch() {
		try {
			$basePath = $this->request->getBasePath() . '/';
			$requestURI = $this->request->getRequestUri();

			$requestPath = substr($requestURI, strlen($basePath));
			$parts = explode('/', $requestPath);

			$controllerName = (isset($parts[0]) && preg_match('#^[a-z]+$#', $parts[0])) ? (string) $parts[0] : 'index';
			$class = 'Modcasts\Controller\\' . ucfirst($controllerName) . 'Controller';
			if ( ! class_exists($class)) {
				throw new FileNotFoundException;
			}
			
			$actionName = (isset($parts[1]) && preg_match('#^[a-z]+$#', $parts[1])) ? (string) $parts[1] : 'index';
			$method = $actionName . 'Action';
			$reflectionClass = new \ReflectionClass($class);
			if ( ! $reflectionClass->hasMethod($method)) {
				throw new FileNotFoundException;
			}
			
			$resource = (isset($parts[2]) && is_numeric($parts[2])) ? (int) $parts[2] : null;
			
			$controller = $reflectionClass->newInstance($this->request, $this->env);
			
			return $controller->$method($resource);
		} catch (FileNotFoundException $e) {
			$controller = new Controller\ErrorController($this->request, $this->env, $e);
			return $controller->fileNotFoundAction();
		}
	}
}