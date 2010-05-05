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

class Atom {
	public $xml;
	
	const CONTENT_TYPE = 'application/atom+xml';
	
	public function __construct(Feed $feed) {
		$this->xml = new \XMLWriter;
		$this->xml->openMemory();
		$this->xml->setIndent(true);
		$this->xml->setIndentString("\t");
		$this->xml->startDocument('1.0', 'UTF-8');
		
		$this->writeFeed($feed);
	}
	
	protected function writeFeed(Feed $feed) {
		$this->xml->startElement('feed');
		$this->xml->writeAttribute('xmlns',
			'http://www.w3.org/2005/Atom');
		
		$this->xml->writeElement('id', $this->generateUUID($feed->id));
		
		$this->xml->writeElement('title', $feed->title);
		
		$this->xml->startElement('link');
		$this->xml->writeAttribute('href', $feed->link);
		$this->xml->endElement();
		
		$this->xml->writeElement('updated',
			$this->formatDate($feed->updated));
		
		foreach ($feed->authors as $author) {
			$this->writeAuthor($author);
		}
		
		foreach ($feed->entries as $item) {
			$this->writeItem($item);
		}
	}
	
	protected function writeAuthor(Author $author) {
		$this->xml->startElement('author');
		$this->xml->writeElement('name', $author->name);
		$this->xml->endElement();
	}
	
	protected function writeItem(FeedItem $item) {
		$this->xml->startElement('entry');
		
		$this->xml->writeElement('id', $this->generateUUID($item->id));
		
		$this->xml->writeElement('title', $item->title);
		
		$this->xml->startElement('link');
		$this->xml->writeAttribute('href', $item->link);
		$this->xml->endElement();
		
		$this->xml->writeElement('updated',
			$this->formatDate($item->updated));
		
		$this->xml->writeElement('summary', $item->summary);
		
		$this->xml->endElement();
	}
	
	protected function formatDate($date) {
		return $date->format(\DateTime::ATOM);
	}
	
	protected function generateUUID($value) {
		return 'urn:uuid:' . \UUID::generate(\UUID::UUID_NAME_SHA1, \UUID::FMT_STRING,
			$value);
	}
	
	public function dump() {
		$this->xml->endElement();
		$this->xml->endDocument();
		return $this->xml->outputMemory();
	}
	
	public function getContentType() {
		return self::CONTENT_TYPE;
	}
}
