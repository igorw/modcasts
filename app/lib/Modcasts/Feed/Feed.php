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

class Feed {
	public $id = 0;
	public $title;
	public $link;
	public $updated; /* DateTime */
	public $authors = array();
	public $entries = array();
}
