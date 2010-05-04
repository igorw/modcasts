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

class CSRFTokenFactory {
	private $secretToken;
	private $created;
	
	public function __construct($secretToken, $created = null) {
		$this->secretToken = $secretToken;
		$this->created = $created ? $created : time();
	}
	
	public function getToken($formName) {
		return new CSRFToken($formName, $this->secretToken, $this->created);
	}
}
