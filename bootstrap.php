<?php
/**
 * This file is part of modcasts.
 *
 * (c) 2010 Igor Wiedler
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Symfony\Foundation\UniversalClassLoader;

require __DIR__ . '/vendor/Symfony/src/Symfony/Foundation/UniversalClassLoader.php';

$loader = new UniversalClassLoader;
$loader->registerNamespaces(array(
	'Symfony'		=> __DIR__ . '/vendor/Symfony/src',
));
$loader->registerPrefixes(array(
	'Twig_'			=> __DIR__ . '/vendor/Twig/lib',
));
$loader->register();
