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
use NxtLvlSoftware\LaravelModulesCli\Setting\FileSettings;

class FileGenerator extends AbstractGenerator {

	/**
	 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\FileSettings
	 */
	private $settings;

	public function __construct(Filesystem $filesystem, FileSettings $settings) {
		parent::__construct($filesystem);

		$this->settings = $settings;
	}

	public function generate() : void {
		$this->getFilesystem()->put(
			$this->settings->getOutput(),
			$this->settings->getView()->render()
		);
	}

}