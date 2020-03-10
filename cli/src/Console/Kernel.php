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

namespace NxtLvlSoftware\LaravelModulesCli\Console;

use Illuminate\Console\Application as Artisan;
use Illuminate\Foundation\Console\Kernel as BaseKernel;
use NxtLvlSoftware\LaravelModulesCli\Console\Command\GenerateFileCommand;
use NxtLvlSoftware\LaravelModulesCli\Console\Command\GenerateModelFileCommand;
use NxtLvlSoftware\LaravelModulesCli\Console\Command\MakeModuleCommand;
use NxtLvlSoftware\LaravelModulesCli\Setting\File\ClassFileSettings;

class Kernel extends BaseKernel {

	/**
	 * The bootstrap classes for the application.
	 *
	 * @var array
	 */
	protected $bootstrappers = [
		\Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
		\Illuminate\Foundation\Bootstrap\HandleExceptions::class,
		\Illuminate\Foundation\Bootstrap\RegisterFacades::class,
		\Illuminate\Foundation\Bootstrap\RegisterProviders::class,
		\Illuminate\Foundation\Bootstrap\BootProviders::class,
	];

	/**
	 * Register all the commands provided by the kernel to the console application.
	 */
	public static function registerCommands(Artisan $artisan) : void {
		// register normal class commands
		$artisan->add(new MakeModuleCommand());

		// register simple class template creation commands
		$artisan->add(new GenerateFileCommand("command", "Create a new console command.", "src/Console/Command/Command.php", ClassFileSettings::class));
		$artisan->add((new GenerateModelFileCommand("factory", "Create a new model factory.", "database/factories/Factory.php"))
			->prependBase(false)
			->appendBase(true)
		);
		$artisan->add((new GenerateFileCommand("model", "Create a new eloquent model.", "src/Model/Model.php", ClassFileSettings::class))
			->prependBase(false)
		);
		$artisan->add((new GenerateFileCommand("provider", "Create a new service provider.", "src/Provider/ServiceProvider.php", ClassFileSettings::class))
			->prependBase(false)
			->appendBase(true)
			->after(static function(GenerateFileCommand $command) : void {
				$file = $command->getComposerSettings();
				$file->merge([
					"extra" => [
						"laravel" => [
							"providers" => [
								$command->getFileSettings()->getFqn(),
							],
						],
					],
				]);
				$file->toFile($command->getModuleDisk());
			}));
	}

}