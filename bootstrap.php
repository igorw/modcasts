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

require __DIR__ . '/vendor/PHP_Markdown/markdown.php';


$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__."/app/model"));
$config->setMetadataDriverImpl($driverImpl);

$loader = new \Modcasts\ClassLoader('Modcasts\Proxy', __DIR__ . '/cache/doctrine', true);
$loader->register();

$config->setProxyDir(__DIR__ . '/cache/doctrine');
$config->setProxyNamespace('Modcasts\Proxy');

$connectionOptions = array(
    'driver' => 'pdo_sqlite',
    'path' => 'database.sqlite'
);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
