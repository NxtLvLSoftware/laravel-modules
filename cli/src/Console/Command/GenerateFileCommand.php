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
use NxtLvlSoftware\LaravelModulesCli\Console\Command\Traits\HasNamedCallbacks;
use NxtLvlSoftware\LaravelModulesCli\Console\Extension\Argument\NameArgument;
use NxtLvlSoftware\LaravelModulesCli\Console\Extension\FileSettings as FileSettingsExtension;
use NxtLvlSoftware\LaravelModulesCli\Console\Extension\Option\NamespaceOption;
use NxtLvlSoftware\LaravelModulesCli\Console\Extension\Option\StructureOption;
use NxtLvlSoftware\LaravelModulesCli\Console\Traits\RequiresModuleSettings;
use NxtLvlSoftware\LaravelModulesCli\Generator\FileGenerator;
use NxtLvlSoftware\LaravelModulesCli\Setting\FileSettings;
use function array_merge;

/**
 * Generic command implementation for generating files from blade templates.
 */
class GenerateFileCommand extends BaseCommand {
	use HasNamedCallbacks, RequiresModuleSettings;

	/**
	 * @var string
	 */
	protected $baseName;

	/**
	 * @var string
	 */
	protected $template;

	/**
	 * @var string|null
	 */
	protected $fileSettings;

	protected const CALLBACK_NAME = "format_name";

	protected function fallbackDefinition(string $description, string $signature = null) : void {
		$this->description = $this->description ?? $description;
		$this->signature = $this->signature ?? ($signature ?? ("make:" . $this->baseName));
	}

	protected function defaultExtensions() : array {
		return array_merge(parent::defaultExtensions(), [
			new NameArgument($this->baseName),
			new NamespaceOption,
			new StructureOption,
			new FileSettingsExtension($this->template, $this->fileSettings, static function (GenerateFileCommand $command) : string {
				return $command->runNameFormatCallback(NameArgument::valueFor($command));
			})
		]);
	}

	public function __construct(string $name, string $description, string $template, ?string $fileSettings = null) {
		$this->baseName = Str::lower($name);
		$this->template = $template;
		$this->fileSettings = $fileSettings;

		$this->fallbackDefinition($description);

		parent::__construct();
	}

	protected function exec() : void {
		$generator = new FileGenerator(
			$this->getModuleDisk(),
			$this->getFileSettings()
		);

		$generator->generate();
	}

	public function getFileSettings() : FileSettings {
		return FileSettingsExtension::valueFor($this);
	}

	/**
	 * Callback that is called when resolving the output file name. Can be used
	 * to set the output file name or just listen for name resolving.
	 *
	 * @param callable $callback
	 *
	 * @return static
	 */
	public function withNameFormat(callable $callback) : self {
		$this->setNamedCallback(self::CALLBACK_NAME, $callback);

		return $this;
	}

	/**
	 * Run the out file name format callback and fallback to the ordinal name if
	 * it returns null or isn't found.
	 */
	protected function runNameFormatCallback(string $name) : string {
		return $this->callNamedCallback(self::CALLBACK_NAME, $this, $name) ?? $name;
	}

}