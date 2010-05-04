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

use Symfony\Framework\WebBundle\Session\NativeSession;

use Twig_Environment,
	Twig_Loader_Filesystem;

require __DIR__ . '/bootstrap.php';

$twig = new Twig_Environment(new Twig_Loader_Filesystem('app/view'), array(
	'cache'	=> __DIR__ . '/cache/twig',
	'debug'	=> true,
));
$twig->addExtension(new TwigExtension());

$sessionFactory = function() {
	return new NativeSession(array(
		'session_name'	=> 'MODCASTS_SESSION',
	));
};

$env = new Environment(__DIR__, $twig, $em, $sessionFactory, $appConfig);

$request = new Request;

$router = new Router($request, $env);
echo $router->dispatch();
