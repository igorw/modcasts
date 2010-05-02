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

class Episode {
	public $id;
	public $title;
	public $created_at;
	public $show_notes;
	public $file_bytes;
	public $theme_artist;
	public $theme_license;
}
