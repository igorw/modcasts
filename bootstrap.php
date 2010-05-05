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

use Symfony\Foundation\UniversalClassLoader;

require __DIR__ . '/vendor/Symfony/src/Symfony/Foundation/UniversalClassLoader.php';

$loader = new UniversalClassLoader;
$loader->registerNamespaces(array(
	'Modcasts'		=> __DIR__ . '/app/lib',
	'Symfony'		=> __DIR__ . '/vendor/Symfony/src',
	'Doctrine'		=> __DIR__ . '/vendor/Doctrine/lib',
));
$loader->registerPrefixes(array(
	'Twig_'			=> __DIR__ . '/vendor/Twig/lib',
));
$loader->register();

$loader = new ClassLoader('Modcasts\Entities', __DIR__ . '/app/model', true);
$loader->register();

$loader = new ClassLoader('Modcasts\Controller', __DIR__ . '/app/controller', true);
$loader->register();

$loader = new ClassLoader('Modcasts\Proxy', __DIR__ . '/cache/doctrine', true);
$loader->register();

require __DIR__ . '/vendor/PHP_Markdown/markdown.php';
require __DIR__ . '/vendor/UUID/UUID.php';


$container = new \Symfony\Components\DependencyInjection\Builder;
$loader = new \Symfony\Components\DependencyInjection\Loader\YamlFileLoader;
$container->merge($loader->load(__DIR__ . '/config/services.yml'));
