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

/**
 * @Entity(repositoryClass="Modcasts\Entities\Repository\EpisodeRepository")
 * @HasLifecycleCallbacks
 */
class Episode {
	/** @Id @Column(type="integer") */
	public $id;
	/** @Column(length=100) */
	public $title;
	/** @Column(type="datetime") */
	public $created;
	/** @Column(type="datetime") */
	public $updated;
	/** @Column(type="text") */
	public $show_notes;
	/** @Column(type="integer") */
	public $file_bytes = 0;
	/** @OneToOne(targetEntity="Artist") */
	public $theme_artist;
	/** @OneToOne(targetEntity="License") */
	public $theme_license;
	
	public function __construct() {
		$this->created = $this->updated = new \DateTime("now");
	}
	
	/** @PreUpdate */
	public function updated() {
		$this->updated = new \DateTime("now");
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getCreated() {
		return $this->created;
	}
	
	public function getShowNotes() {
		return $this->show_notes;
	}
	
	public function getFileBytes() {
		return $this->file_bytes;
	}
	
	public function getThemeArtist() {
		return $this->theme_artist;
	}
	
	public function getThemeLicense() {
		return $this->theme_license;
	}
	
	public function validate() {
		$errors = array();
		
		if ( ! $this->title) {
			$errors[] = 'Please enter a title';
		}
		if ( ! $this->show_notes) {
			$errors[] = 'Please enter show notes';
		}
		if ( ! $this->theme_artist) {
			$errors[] = 'Invalid theme artist specified';
		}
		if ( ! $this->theme_license) {
			$errors[] = 'Invalid theme license specified';
		}
		
		return $errors;
	}
}
