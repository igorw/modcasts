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

class JsonEntityManager implements EntityManager {
	private $storePath;
	
	public function __construct($storePath) {
		$this->storePath = $storePath;
	}
	
	public function findAll() {
		$entities = array();
		
		$iterator = new \DirectoryIterator($this->storePath);
		foreach ($iterator as $file) {
			if ($file->isDot()) {
				continue;
			}
			$filename = $file->getPathname();
			$entities[] = $this->fromJsonFile($filename);
		}
		
		return $entities;
	}
	
	public function find($id) {
		$filename = $this->storePath . $id . '.json';
		return $this->fromJsonFile($filename);
	}
	
	public function save($entity) {
		// TODO
	}
	
	public function fromJsonFile($filename) {
		$contents = file_get_contents($filename);
		return $this->fromJson($contents);
	}
	
	public function fromJson($json) {
		$entity = json_decode($json);
		return $entity;
	}
}
