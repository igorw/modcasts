<?php
/**
 * This file is part of modcasts.
 *
 * (c) 2010 Igor Wiedler
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Modcasts\Entities;

/** @Entity */
class Artist {
	/** @Id @Column(type="integer") @GeneratedValue(strategy="AUTO") */
	public $id;
	/** @Column(length=100) */
	public $name;
	/** @Column(length=255) */
	public $website;
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getWebsite() {
		return $this->website;
	}
}
