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

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use NxtLvlSoftware\LaravelModulesCli\Console\Traits\RequiresModuleFilesystem;
use NxtLvlSoftware\LaravelModulesCli\Generator\FileGenerator;
use NxtLvlSoftware\LaravelModulesCli\Generator\ModuleGenerator;
use NxtLvlSoftware\LaravelModulesCli\Setting\File\ClassFileSettings;
use NxtLvlSoftware\LaravelModulesCli\Setting\File\ComposerJsonFileSettings;
use NxtLvlSoftware\LaravelModulesCli\Setting\File\NamedClassFileSettings;
use NxtLvlSoftware\LaravelModulesCli\Setting\ModuleSettings;
use function getcwd;
use const DIRECTORY_SEPARATOR;

class MakeServiceProviderCommand extends Command {
	use RequiresModuleFilesystem;

	protected $signature = "make:provider {name} {--p|path=}";

	protected $description = "Create a new module.";

	public function handle() : void {
		$rootPath = getcwd();
		$filesystem = $this->createModuleFilesystem($rootPath);

		$composer = new ComposerJsonFileSettings(null, $rootPath);
		$composer->fromFile($filesystem);

		$name = $this->name();
		$path = $rootPath . "/src/" . $this->path();

		$generator = new FileGenerator(
			$this->createModuleFilesystem(getcwd()),
			(new NamedClassFileSettings(
				new ModuleSettings($composer->getPackage(), $rootPath, $composer->detectNamespace()),
				"src/Provider/ServiceProvider.php"
			))->setName($name)
		);
		$generator->generate();
	}

	/**
	 * Resolve the name argument value.
	 */
	private function name() : string {
		return $this->argument("name");
	}

	/**
	 * Resolve the namespace option value.
	 */
	private function path() : string {
		$opt = $this->option("path");

		if($opt !== null) {
			return $opt;
		}

		return "Provider";
	}

}