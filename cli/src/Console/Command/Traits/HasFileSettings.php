<?php

declare(strict_types=1);

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

namespace NxtLvlSoftware\LaravelModulesCli\Console\Command\Traits;

use NxtLvlSoftware\LaravelModulesCli\Setting\FileSettings;
use function is_a;

/**
 * @mixin \NxtLvlSoftware\LaravelModulesCli\Console\Command\BaseCommand
 */
trait HasFileSettings {

	/**
	 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\FileSettings
	 */
	private $fileSettings;

	/**
	 * @var bool
	 */
	protected $prependBase = true;

	/**
	 * @var bool
	 */
	protected $appendBase = false;

	private function createFileSettings(string $template, ?string $class) : void {
		if($this->fileSettings !== null) {
			return;
		}

		if($class === null or !is_a($class, FileSettings::class, true)) {
			$class = FileSettings::class;
		}

		$this->fileSettings = new $class($this->makeModuleSettings(), $template);

		$this->fileSettings->prependBase($this->prependBase);
		$this->fileSettings->appendBase($this->appendBase);
	}

	public function getFileSettings() : FileSettings {
		return $this->fileSettings;
	}

	public function prependBase(bool $prepend = true) : self {
		$this->prependBase = $prepend;

		return $this;
	}

	public function appendBase(bool $append = true) : self {
		$this->appendBase = $append;

		return $this;
	}

}