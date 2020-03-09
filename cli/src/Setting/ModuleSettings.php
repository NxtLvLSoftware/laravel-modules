<?php

declare(strict_types=1);

/**
 * Copyright (C) 2020 NxtLvL Software Solutions
 *
 * @author Jack Noordhuis <me@jacknoordhuis.net>
 * @copyright NxtLvL Software Solutions
 *
 * This is free and unencumbered software released into the public domain.
 *
 * Anyone is free to copy, modify, publish, use, compile, sell, or
 * distribute this software, either in source code form or as a compiled
 * binary, for any purpose, commercial or non-commercial, and by any means.
 *
 * In jurisdictions that recognize copyright laws, the author or authors
 * of this software dedicate any and all copyright interest in the
 * software to the public domain. We make this dedication for the benefit
 * of the public at large and to the detriment of our heirs and
 * successors. We intend this dedication to be an overt act of
 * relinquishment in perpetuity of all present and future rights to this
 * software under copyright law.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * For more information, please refer to <http://unlicense.org/>
 *
 */

namespace NxtLvlSoftware\LaravelModulesCli\Setting;

use function is_a;
use function is_iterable;
use function is_string;
use const DIRECTORY_SEPARATOR;

class ModuleSettings {

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var string
	 */
	private $ns;

	/**
	 * @var array
	 */
	private $structure;

	public function __construct(string $path, string $namespace, ?array $structure = null) {
		$this->path = $path;
		$this->ns = $namespace;
		$this->structure = $structure ?? $this->defaultStructure();
	}

	/**
	 * Returns the root path for the module.
	 */
	public function getPath() : string {
		return $this->path;
	}

	/**
	 * Returns the root namespace for the module.
	 */
	public function getNamespace() : string {
		return $this->ns;
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
	 * @return \NxtLvlSoftware\LaravelModulesCli\Setting\FileSettings[]|\Generator
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
				if(is_a($value, FileSettings::class, true)){
					$class = $value;
					$outName = $key;
				} else{
					$class = FileSettings::class;
					$outName = $value;
				}

				yield new $class($this, $root . DIRECTORY_SEPARATOR . $outName);
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