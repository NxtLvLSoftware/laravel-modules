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

use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use NxtLvlSoftware\LaravelModulesCli\Generator\Exception\FileNotCreatedException;
use NxtLvlSoftware\LaravelModulesCli\Generator\FileGenerator;
use NxtLvlSoftware\LaravelModulesCli\Setting\FileSettings;
use function basename;
use function dirname;
use function str_replace;
use function trim;

trait GeneratesFiles {

	/**
	 * Generate a new directory and throw an exception on failure.
	 *
	 * @param string $path The full path to the directory to be created.
	 *
	 * @throws \NxtLvlSoftware\LaravelModulesCli\Generator\Exception\FileNotCreatedException
	 */
	protected function generateFile(string $path) : void {
		$stub = $this->getStubName($path);
		try {
			(new FileGenerator(
				$this->getFilesystem(),
				new FileSettings($stub, $path)
			))
				->generate();
		} catch(Exception $e) {
			throw new FileNotCreatedException("Could not create file '{$path}' from stub '{$stub}'", 0, $e);
		}
	}

	/**
	 * Get a stub filename from the file path relative to the module project.
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	private function getStubName(string $path) : string {
		$name = str_replace(".", "_", trim(basename($path), ".")); // replace file extension separator '.' with '_'
		$base = str_replace("/", ".", trim(dirname($path), "/")); // replace path separators '/' with '.'

		return $base . "." . $name;
	}

	abstract public function getFileSystem() : Filesystem;

}