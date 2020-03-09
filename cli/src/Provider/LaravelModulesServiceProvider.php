<?php

declare(strict_types=1);

/**
 * Copyright (C) 2018–2020 NxtLvL Software Solutions
 *
 * This is private software, you cannot redistribute and/or modify it in any way
 * unless given explicit permission to do so. If you have not been given explicit
 * permission to view or modify this software you should take the appropriate actions
 * to remove this software from your device immediately.
 *
 * @author Jack Noordhuis
 *
 */

namespace NxtLvlSoftware\LaravelModulesCli\Provider;

use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Support\AggregateServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\View\ViewServiceProvider;
use const DIRECTORY_SEPARATOR;

class LaravelModulesServiceProvider extends AggregateServiceProvider {

	/**
	 * List of providers the application requires.
	 *
	 * @var array
	 */
	protected $providers = [
		// external packages that should be loaded before the rest of the app
		FilesystemServiceProvider::class,
		ViewServiceProvider::class,
	];

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		parent::register();
		$this->bindStubViews();
	}

	/**
	 * Bind the CLI's view stubs to the containers view instance.
	 */
	private function bindStubViews() : void {
		Config::set("view.paths", [__DIR__ . "/../../stubs"]);
		Config::set("view.compiled", __DIR__ . "/../../bootstrap/cache/views");
	}

}