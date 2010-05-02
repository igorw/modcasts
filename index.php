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

use Twig_Environment,
	Twig_Loader_Filesystem;

require __DIR__ . '/bootstrap.php';

$twig = new Twig_Environment(new Twig_Loader_Filesystem('app/view'), array(
	'cache'	=> __DIR__ . '/cache/twig',
	'debug'	=> true,
));
$twig->addExtension(new TwigExtension());

$em = new JsonEntityManager(__DIR__ . '/data/episodes_meta');

$episodes = $em->findAll();
usort($episodes, function($a, $b) {
	// sort by date descending
	if ($a->created_at == $b->created_at) {
		return 0;
	}
	return ($a->created_at > $b->created_at) ? -1 : 1;
});

$template = $twig->loadTemplate('index.html');
echo $template->render(array(
	'episodes'	=> $episodes,
));
