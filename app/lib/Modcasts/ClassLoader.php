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

class ClassLoader {
	private $namespace;
	private $path;
	private $pathStripNamespace;
	
	public function __construct($namespace, $path, $pathStripNamespace = false) {
		$this->namespace = $namespace;
		$this->path = (substr($path, -1) == '/') ? $path : $path . '/';
		$this->pathStripNamespace = $pathStripNamespace;
	}
	
	public function loadClass($class) {
		if (preg_match('#^' . preg_quote($this->namespace . '\\') . '#', $class)) {
			if ($this->pathStripNamespace) {
				$class = substr($class, strlen($this->namespace) + 1);
			}
			$filename = $this->path . str_replace('\\', '/', $class) . '.php';
			if (is_readable($filename)) {
				require $filename;
			}
		}
	}
	
	public function register() {
		spl_autoload_register(array($this, 'loadClass'));
	}
	
	public function unregister() {
		spl_autoload_unregister(array($this, 'autoload'));
	}
}
