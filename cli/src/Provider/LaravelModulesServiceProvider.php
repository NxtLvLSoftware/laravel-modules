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

namespace NxtLvlSoftware\LaravelModulesCli\Provider;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Support\AggregateServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\View\ViewServiceProvider;
use NxtLvlSoftware\LaravelModulesCli\Setting\File\ComposerJsonFileSettings;
use function getcwd;

class LaravelModulesServiceProvider extends AggregateServiceProvider {

	public const MODULE_DISK_PATH = "module_disk_path";
	public const MODULE_DISK = "module_disk";

	/**
	 * List of providers the application requires.
	 *
	 * @var array
	 */
	protected $providers = [
		// external packages that should be loaded before the rest of the app
		FilesystemServiceProvider::class,
		ViewServiceProvider::class,

		// providers for this package
		LaravelModulesCommandServiceProvider::class,
	];

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		parent::register();

		$this->bindStubViews();
		$this->bindModuleDisk();
		$this->bindComposerJson();

		$this->bladeDirectives();
	}

	/**
	 * Bind the CLI's view stubs to the containers view instance.
	 */
	private function bindStubViews() : void {
		Config::set("view.paths", [__DIR__ . "/../../stubs"]);
		Config::set("view.compiled", __DIR__ . "/../../bootstrap/cache/views");
	}

	/**
	 * Bind the module disk to the container.
	 */
	private function bindModuleDisk() : void {
		$this->app->instance(self::MODULE_DISK_PATH, getcwd());

		$this->app->bind(self::MODULE_DISK, static function(Application $app) : Filesystem {
			/** @var \Illuminate\Filesystem\FilesystemManager $manager */
			$manager = $app->make("filesystem");
			$manager->set("module", $filesystem = $manager->createLocalDriver([
				"root" => $app->make(self::MODULE_DISK_PATH)
			]));

			return $manager->disk("module");
		});
	}

	/**
	 * Bind the composer json file instance to the container if it exists.
	 */
	private function bindComposerJson() : void {
		$this->app->bind(ComposerJsonFileSettings::class, static function(Application $app) : ComposerJsonFileSettings {
			$instance = new ComposerJsonFileSettings(null, getcwd());
			$instance->fromFile($app->make(self::MODULE_DISK));

			return $instance;
		});
	}

	/**
	 * Register the applications custom blade directives.
	 */
	protected function bladeDirectives() : void {
		Blade::directive("var", static function (string $type) {
			return "@var " . $type;
		});

		Blade::directive("return", static function (string $type) {
			return "@return " . $type;
		});
	}

}