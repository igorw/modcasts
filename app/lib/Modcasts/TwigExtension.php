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

class TwigExtension extends \Twig_Extension {
	public function getFilters() {
		return array(
			'filesize'	=> new \Twig_Filter_Method($this, 'bytesToString'),
		);
	}
	
	public function getName() {
		return 'Modcasts';
	}
	
	public function bytesToString($bytes) {
		$units = array('B', 'KiB', 'MiB', 'GiB', 'TiB');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		$bytes /= pow(1024, $pow);

		return round($bytes, 0) . ' ' . $units[$pow];
	}
}
