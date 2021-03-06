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

require __DIR__ . '/bootstrap.php';

$request = $container->request;

$router = new Router($request, $container);
echo $router->dispatch();
