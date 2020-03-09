<?php

declare(strict_types=1);

/**
 * Copyright (C) 2018–2020 NxtLvL Software Solutions
 *
 * This is private software, you cannot redistribute and/or modify it in any way
 * unless given explicit permission to do so. If you have not been given explicit
 * permission to view or modify this software you should take the appropriate actions
 * to remove this software from your device immediately.
 *
 * @author Jack Noordhuis
 *
 */

namespace NxtLvlSoftware\LaravelModulesCli\Setting;

use function is_iterable;
use function is_string;
use const DIRECTORY_SEPARATOR;

class ModuleSettings {

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var array
	 */
	private $structure;

	public function __construct(string $path, ?array $structure = null) {
		$this->path = $path;
		$this->structure = $structure ?? $this->defaultStructure();
	}

	/**
	 * Returns the root path for the module.
	 *
	 * @return string
	 */
	public function getPath() : string {
		return $this->path;
	}

	/**
	 * Returns an @link \Generator for resolving directories in the module or a sub-directory.
	 *
	 * @param string   $root
	 * @param iterable $map
	 * @phpstan-param iterable<string|int, string|iterable>
	 *
	 * @return string[]|\Generator
	 */
	public function directories(string $root = "", iterable $map = null) : iterable {
		foreach($map ?? $this->structure as $key => $value) {
			// has nested paths or files
			if(is_iterable($value)){
				$path = $root . DIRECTORY_SEPARATOR . $key;
				yield $path; // yield the current dir
				foreach($this->directories($path, $value) as $directory){ // yield sub directories recursively
					yield $directory;
				}
			}
		}
	}

	/**
	 * Returns an @link \Generator for resolving files in the module or a sub-directory.
	 *
	 * @param string   $root
	 * @param iterable $map
	 * @phpstan-param iterable<string|int, string|iterable>
	 *
	 * @return string[]|\Generator
	 */
	public function files(string $root = "", iterable $map = null) : iterable {
		foreach($map ?? $this->structure as $key => $value) {
			// has nested paths or files
			if(is_iterable($value)){
				$path = $root . DIRECTORY_SEPARATOR . $key;
				foreach($this->files($path, $value) as $file){ // yield sub directories recursively
					yield $file;
				}
			} elseif(is_string($value)) {
				yield $root . DIRECTORY_SEPARATOR . $value;
			}
		}
	}

	/**
	 * Returns the default module project structure.
	 *
	 * @return iterable[]|string[]
	 * @phpstan-return iterable<string|int, string|iterable>
	 */
	protected function defaultStructure() : iterable {
		return include __DIR__ . "/../../default_structure.php";
	}

}