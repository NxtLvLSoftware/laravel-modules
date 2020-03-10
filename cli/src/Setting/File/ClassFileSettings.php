<?php

/**
 * Copyright (C) 2020 NxtLvL Software Solutions
 *
 * @author    Jack Noordhuis <me@jacknoordhuis.net>
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

namespace NxtLvlSoftware\LaravelModulesCli\Setting\File;

use NxtLvlSoftware\LaravelModulesCli\Setting\FileSettings;
use function pathinfo;
use function str_replace;
use function trim;
use const PATHINFO_DIRNAME;
use const PATHINFO_FILENAME;

/**
 * Settings for class-like namespaced files (classes, traits, interfaces, etc).
 */
class ClassFileSettings extends FileSettings {

	/**
	 * @var string
	 */
	private $ns;

	/**
	 * @var string|null
	 */
	private $outputClassName = null;

	protected function init() : void {
		$this->ns = $this->resolveNamespace();
	}

	public function getNamespace() : string {
		return $this->ns;
	}

	public function getClassName() : string {
		return $this->outputClassName ?? $this->getName();
	}

	public function setOutputClassName(string $name) : void {
		$this->outputClassName = $name;
	}

	public function getFqn() : string {
		return $this->getNamespace() . "\\" . $this->getClassName();
	}

	private function resolveNamespace() : string {
		$root = trim(pathinfo($this->getOutput(), PATHINFO_DIRNAME) . "/", "/src/"); // strip /src prefix from path
		$ns = str_replace("/", "\\", $root); // replace path separator with namespace separator

		return $this->getModuleSettings()->getNamespace() . "\\" . $ns; // prepend module namespace
	}

}