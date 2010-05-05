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

use Symfony\Components\DependencyInjection\ContainerInterface as Container;

class Router {
	private $request;
	private $container;
	
	public function __construct(Request $request, Container $container) {
		$this->request = $request;
		$this->container = $container;
	}
	
	public function dispatch() {
		try {
			$basePath = $this->request->getBasePath() . '/';
			$requestURI = $this->request->getRequestUri();

			$requestPath = substr($requestURI, strlen($basePath));
			if (false !== ($pos = strpos($requestPath, '?'))) {
				$requestPath = substr($requestPath, 0, $pos);
			}
			$parts = explode('/', $requestPath);

			$controllerName = (isset($parts[0]) && preg_match('#^[a-z]+$#', $parts[0])) ? (string) $parts[0] : 'index';
			$class = 'Modcasts\Controller\\' . ucfirst($controllerName) . 'Controller';
			if ( ! class_exists($class)) {
				throw new FileNotFoundException;
			}
			
			$actionName = (isset($parts[1]) && preg_match('#^[a-zA-Z]+$#', $parts[1])) ? (string) $parts[1] : 'index';
			$method = $actionName . 'Action';
			$reflectionClass = new \ReflectionClass($class);
			if ( ! $reflectionClass->hasMethod($method)) {
				throw new FileNotFoundException;
			}
			
			$resource = (isset($parts[2]) && is_numeric($parts[2])) ? (int) $parts[2] : null;
			
			$controller = $reflectionClass->newInstance($this->request, $this->container);
			
			return $controller->$method($resource);
		} catch (RedirectException $e) {
			return $this->redirect($this->request->getBasePath() . '/' . $e->getURL());
		} catch (FileNotFoundException $e) {
			$controller = new Controller\ErrorController($this->request, $this->container, $e);
			return $controller->fileNotFoundAction();
		} catch (\Exception $e) {
			$controller = new Controller\ErrorController($this->request, $this->container, $e);
			return $controller->exceptionAction();
		}
	}
	
	public function redirect($url) {
		$response = new Response;
		$response->setStatusCode(302);
		$response->setHeader('Location', $url);
		return $response;
	}
}
