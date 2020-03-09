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
use NxtLvlSoftware\LaravelModulesCli\Generator\Traits\GeneratesDirectories;
use NxtLvlSoftware\LaravelModulesCli\Generator\Traits\GeneratesFiles;
use NxtLvlSoftware\LaravelModulesCli\Setting\ModuleSettings;

class ModuleGenerator extends AbstractGenerator {
	use GeneratesDirectories, GeneratesFiles;

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
			$this->generateFile($file);
		}
	}

}