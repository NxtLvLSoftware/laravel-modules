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

namespace NxtLvlSoftware\LaravelModulesCli\Generator\Traits;

use Illuminate\Contracts\Filesystem\Filesystem;
use NxtLvlSoftware\LaravelModulesCli\Generator\Exception\DirectoryNotCreatedException;
use function mkdir;
use function realpath;
use function var_dump;

trait GeneratesDirectories {

	/**
	 * Generate a new directory and throw an exception on failure.
	 *
	 * @param string $path The full path to the directory to be created.
	 *
	 * @throws \NxtLvlSoftware\LaravelModulesCli\Generator\Exception\DirectoryNotCreatedException
	 */
	protected function generateDirectory(string $path) : void {
		$created = $this->getFileSystem()->makeDirectory($path);

		if(!$created) {
			throw new DirectoryNotCreatedException("Could not create directory '{$path}'");
		}
	}

	abstract public function getFileSystem() : Filesystem;

}