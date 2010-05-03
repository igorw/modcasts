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

class RedirectException extends \Exception {
	private $url;
	
	public function __construct($url) {
		parent::__construct();
		$this->url = $url;
	}
	
	public function getURL() {
		return $this->url;
	}
}
