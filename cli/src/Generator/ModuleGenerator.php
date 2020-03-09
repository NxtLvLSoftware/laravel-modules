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

namespace NxtLvlSoftware\LaravelModulesCli\Generator;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\View;
use NxtLvlSoftware\LaravelModulesCli\Generator\Traits\GeneratesDirectories;
use NxtLvlSoftware\LaravelModulesCli\Setting\ModuleSettings;
use function str_replace;
use function var_dump;
use const DIRECTORY_SEPARATOR;

class ModuleGenerator extends AbstractGenerator {
	use GeneratesDirectories;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\ModuleSettings
	 */
	private $settings;

	public function __construct(string $name, Filesystem $filesystem, ModuleSettings $settings) {
		parent::__construct($filesystem);

		$this->name = $name;
		$this->settings = $settings;
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return $this->name;
	}

	/**
	 * Generate the module from the provide settings object.
	 */
	public function generate() : void {
		foreach($this->settings->directories() as $path) {
			$this->generateDirectory($path);
		}
		foreach($this->settings->files() as $file) {
			// TODO: generate file from template
			$stub = str_replace([".php", ".js", ".sass", "/"], ["", "_js", "_sass", "."], trim($file, "/"));
			$this->getFilesystem()->put($file, View::make($stub)->render());
		}
	}

}