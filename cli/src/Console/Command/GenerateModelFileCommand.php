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

use NxtLvlSoftware\LaravelModulesCli\Console\Extension\Argument\ModelArgument;
use NxtLvlSoftware\LaravelModulesCli\Console\Extension\FileSettings as FileSettingsExtension;
use NxtLvlSoftware\LaravelModulesCli\Console\Extension\Option\NamespaceOption;
use NxtLvlSoftware\LaravelModulesCli\Console\Extension\Option\StructureOption;
use NxtLvlSoftware\LaravelModulesCli\Setting\File\ClassFileSettings;
use function array_merge;

/**
 * Generic command implementation for generating files from blade templates that depend on a model.
 */
class GenerateModelFileCommand extends GenerateFileCommand {

	protected function defaultExtensions() : array {
		return array_merge(BaseCommand::defaultExtensions(), [
			new ModelArgument($this->baseName),
			new NamespaceOption,
			new StructureOption,
			new FileSettingsExtension($this->template, $this->fileSettings, static function (GenerateFileCommand $command) : string {
				return $command->runNameFormatCallback($this->getModelFileSettings()->getClassName());
			})
		]);
	}

	protected function exec() : void {
		$this->getFileSettings()
			->getView()->with("model", $this->getModelFileSettings());

		parent::exec();
	}

	public function getModelFileSettings() : ClassFileSettings {
		return ModelArgument::valueFor($this);
	}

}