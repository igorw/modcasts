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

use Symfony\Framework\WebBundle\Session\NativeSession;

use Twig_Environment,
	Twig_Loader_Filesystem;

require __DIR__ . '/bootstrap.php';

$env = new Environment($container);

$request = $container->request;

$router = new Router($request, $env);
echo $router->dispatch();
