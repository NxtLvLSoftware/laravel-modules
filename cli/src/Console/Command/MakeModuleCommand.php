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

namespace NxtLvlSoftware\LaravelModulesCli\Console\Command;

use NxtLvlSoftware\LaravelModulesCli\Console\Extension\Argument\NameArgument;
use NxtLvlSoftware\LaravelModulesCli\Console\Extension\Option\NamespaceOption;
use NxtLvlSoftware\LaravelModulesCli\Console\Extension\Option\StructureOption;
use NxtLvlSoftware\LaravelModulesCli\Console\Traits\RequiresModuleSettings;
use NxtLvlSoftware\LaravelModulesCli\Generator\ModuleGenerator;
use function array_merge;
use function getcwd;
use const DIRECTORY_SEPARATOR;

class MakeModuleCommand extends BaseCommand {
	use RequiresModuleSettings;

	protected $signature = "make:module";

	protected $description = "Create a new service provider.";

	protected function defaultExtensions() : array {
		return array_merge(parent::defaultExtensions(), [
			new NameArgument("module"),
			new NamespaceOption,
			new StructureOption,
		]);
	}

	protected function exec() : void {
		$name = NameArgument::retrieve($this);
		$path = getcwd() . DIRECTORY_SEPARATOR . $name;

		$generator = new ModuleGenerator(
			$name,
			$this->getModuleDisk($path),
			$this->makeModuleSettings(
				$name,
				$path,
				NamespaceOption::retrieve($this),
				StructureOption::retrieve($this)
			)
		);
		$generator->generate();
	}

}