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

class CSRFToken {
	private $formName;
	private $secretToken;
	private $created;
	
	public function __construct($formName, $secretToken, $created = null) {
		$this->formName = $formName;
		$this->secretToken = $secretToken;
		$this->created = $created ? $created : time();
	}
	
	public function getCreated() {
		return $this->created;
	}
	
	public function getHash() {
		return hash('sha256', $this->formName . $this->secretToken . $this->created);
	}
	
	public function check($userHash) {
		return $this->getHash() == $userHash;
	}
}
