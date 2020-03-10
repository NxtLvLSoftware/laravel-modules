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

namespace NxtLvlSoftware\LaravelModulesCli\Console\Command;

use Illuminate\Support\Str;
use NxtLvlSoftware\LaravelModulesCli\Console\Command\Traits\HasModelArgument;
use NxtLvlSoftware\LaravelModulesCli\Setting\File\ClassFileSettings;

/**
 * Generic command implementation for generating files from blade templates that depend on a model.
 */
class GenerateModelFileCommand extends GenerateFileCommand {
	use HasModelArgument;

	/**
	 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\File\ClassFileSettings
	 */
	private $modelFileSettings;

	public function __construct(string $name, string $description, string $template, ?string $fileSettings = null) {
		$this->fallbackDefinition(
			"make:" . Str::lower($name) . " {model : Name of the model to create a " . $name . " for} {--p|path=} {--stubs= : Path to the stub directory to use}",
			$description
		);

		parent::__construct($name, $description, $template, $fileSettings);
	}

	protected function exec() : void {
		$this->getFileSettings()->getView()
			->with("model", $this->modelFileSettings = $this->model());

		parent::exec();
	}

	public function getModelFileSettings() : ClassFileSettings {
		return $this->modelFileSettings;
	}

	protected function name() : string {
		return $this->modelFileSettings->getClassName();
	}

}