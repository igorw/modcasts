<?php
/**
 * This file is part of modcasts.
 *
 * (c) 2010 Igor Wiedler
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Modcasts\Feed\Writer;

use Modcasts\Feed\Feed,
	Modcasts\Feed\FeedItem,
	Modcasts\Feed\Author;

interface WriterInterface {
	public function dump();
	public function getContentType();
}
