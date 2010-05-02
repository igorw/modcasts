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
class Episode {
	/** @Id @Column(type="integer") */
	public $id;
	/** @Column(length=100) */
	public $title;
	/** @Column(type="date") */
	public $created_at;
	/** @Column(type="datetime") */
	public $show_notes;
	/** @Column(type="integer") */
	public $file_bytes;
	/** @OneToOne(targetEntity="Artist") */
	public $theme_artist;
	/** @OneToOne(targetEntity="License") */
	public $theme_license;
}
