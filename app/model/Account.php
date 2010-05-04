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
class Account {
	/** @Id @Column(type="integer") @GeneratedValue(strategy="AUTO") */
	public $id;
	/** @Column(length=100) */
	public $username;
	/** @Column(length=100) */
	public $realname;
	/** @Column(length=64) */
	public $password;
	/** @Column(type="datetime") */
	public $created;
	
	public function __construct() {
		$this->created = new \DateTime("now");
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function getRealname() {
		return $this->realname;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function getCreated() {
		return $this->created;
	}
}
