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
use Illuminate\Database\Console\Migrations\TableGuesser;
use Illuminate\Foundation\Console\Kernel as BaseKernel;
use Illuminate\Support\Str;
use NxtLvlSoftware\LaravelModulesCli\Console\Build\CommandBuilder;
use NxtLvlSoftware\LaravelModulesCli\Console\Build\GenerateFileCommandBuilder;
use NxtLvlSoftware\LaravelModulesCli\Console\Command\GenerateFileCommand;
use NxtLvlSoftware\LaravelModulesCli\Console\Command\MakeModuleCommand;
use NxtLvlSoftware\LaravelModulesCli\Console\Traits\RequiresModuleSettings;
use NxtLvlSoftware\LaravelModulesCli\Setting\File\ClassFileSettings;
use function date;
use function trim;

class Kernel extends BaseKernel {
	use RequiresModuleSettings;

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
		$builder = new CommandBuilder($artisan);

		$builder->new(MakeModuleCommand::class);

		self::registerGenerateCommands(GenerateFileCommandBuilder::from($builder));
	}

	/**
	 * Register the file generation commands.
	 */
	private static function registerGenerateCommands(GenerateFileCommandBuilder $builder) : void {
		$builder->file("command", "Create a new console command.", "src/Console/Command/Command.php", ClassFileSettings::class)
			->before(static function(GenerateFileCommand $command) : void {
				$command->getFileSettings()->appendBase();
			});
		$builder->modelFile("factory", "Create a new model factory.", "database/factories/Factory.php")
			->before(static function(GenerateFileCommand $command) : void {
				$command->getFileSettings()->appendBase();
			});
		$builder->file("migration", "Create a new model migration.", "database/migration/migration.php", ClassFileSettings::class)
			->withNameFormat(static function(GenerateFileCommand $command, string $input) : string {
				$name = Str::snake(trim($input));
				[$table, $create] = TableGuesser::guess($name);
				$base = ($create ? "create" : "update") . "_" . $table . "_table";

				$file = $command->getFileSettings();

				$file->getView()->with("table", $table); // set the table name var for the template
				$file->setOutputClassName(Str::studly($base)); // class name

				return date("Y_m_d_His") . "_" . $base; // filename
			});
		$builder->file("model", "Create a new eloquent model.", "src/Model/Model.php", ClassFileSettings::class)
			->withNameFormat(static function(GenerateFileCommand $command, string $input) : string {
				return (string) Str::of($input)->studly();
			});
		$builder->file("provider", "Create a new service provider.", "src/Provider/ServiceProvider.php", ClassFileSettings::class)
			->before(static function(GenerateFileCommand $command) : void {
				$command->getFileSettings()->appendBase();
			})
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
			});
	}

}