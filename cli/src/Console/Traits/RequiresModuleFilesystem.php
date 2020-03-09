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

namespace NxtLvlSoftware\LaravelModulesCli\Console\Traits;

use Illuminate\Contracts\Filesystem\Filesystem;
use function app;
use function getcwd;

trait RequiresModuleFilesystem {

	/**
	 * Create the disk for the module project directory.
	 *
	 * @param string|null $path
	 *
	 * @return \Illuminate\Contracts\Filesystem\Filesystem
	 */
	protected function createModuleFilesystem(string $path = null) : Filesystem {
		if($path === null){
			$path = getcwd();
		}

		/** @var \Illuminate\Filesystem\FilesystemManager $manager */
		$manager = app("filesystem");
		$manager->set("module", $filesystem = $manager->createLocalDriver([
			"root" => $path
		]));

		return $filesystem;
	}

}