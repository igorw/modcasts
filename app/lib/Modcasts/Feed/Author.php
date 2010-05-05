<?php
/**
 * This file is part of modcasts.
 *
 * (c) 2010 Igor Wiedler
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Modcasts\Feed;

class Author {
	public $name;
	// public $uri;
	// public $email;
	
	public function __construct($name) {
		$this->name = $name;
	}
}
