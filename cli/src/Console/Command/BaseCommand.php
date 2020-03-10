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
use Illuminate\Contracts\Filesystem\Filesystem;
use NxtLvlSoftware\LaravelModulesCli\Console\Command\Traits\HasStubsOption;
use NxtLvlSoftware\LaravelModulesCli\Provider\LaravelModulesServiceProvider;
use NxtLvlSoftware\LaravelModulesCli\Setting\File\ComposerJsonFileSettings;
use NxtLvlSoftware\LaravelModulesCli\Setting\ModuleSettings;
use function getcwd;

abstract class BaseCommand extends Command {
	use HasStubsOption;

	/**
	 * @var callable[]
	 */
	private $before = [];

	/**
	 * @var callable[]
	 */
	private $after = [];

	/**
	 * Handle the command execution.
	 */
	final public function handle() : void {
		foreach($this->before as $callable) {
			($callable)($this);
		}

		$this->exec();

		foreach($this->after as $callable) {
			($callable)($this);
		}
	}

	/**
	 * Execute the command.
	 */
	abstract protected function exec() : void;

	/**
	 * Register a callable to be executed before the command has run.
	 */
	public function before(callable $callable) : self {
		$this->before[] = $callable;

		return $this;
	}

	/**
	 * Register a callable to be executed after the command has run.
	 */
	public function after(callable $callable) : self {
		$this->after[] = $callable;

		return $this;
	}

	/**
	 * Retrieve the composer json settings for the current directory.
	 */
	public function getComposerSettings() : ComposerJsonFileSettings {
		return $this->getApplication()->getLaravel()->make(ComposerJsonFileSettings::class);
	}

	/**
	 * Retrieve the module disk from the container.
	 */
	public function getModuleDisk(string $path = null) : Filesystem {
		$app = $this->getApplication()->getLaravel();

		if($path !== null) {
			$app->extend(LaravelModulesServiceProvider::MODULE_DISK_PATH, static function() use($path) : string {
				return $path;
			});
		}

		return $app->make(LaravelModulesServiceProvider::MODULE_DISK);
	}

	/**
	 * Create a module settings instance from the provided parameters or attempt to construct it from the detected composer.json.
	 */
	protected function makeModuleSettings(string $path = null, string $name = null, string $namespace = null, array $structure = null) : ModuleSettings {
		if($name === null or $namespace === null) {
			$composer = $this->getComposerSettings();
		}

		return new ModuleSettings($name ?? $composer->getPackage(), $path ?? getcwd(),$namespace ?? $composer->detectNamespace(), $structure);
	}

}