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