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

namespace NxtLvlSoftware\LaravelModulesCli\Console\Command\Traits;

use InvalidArgumentException;
use NxtLvlSoftware\LaravelModulesCli\Console\Command\BaseCommand;
use NxtLvlSoftware\LaravelModulesCli\Console\Command\Exception\InvalidExtensionValueException;
use NxtLvlSoftware\LaravelModulesCli\Console\Extension\CommandExtension;
use NxtLvlSoftware\LaravelModulesCli\Contract\Console\Extension\Resolvable;
use function array_key_exists;
use function get_class;
use function is_array;
use function is_callable;

trait HasCommandExtensions {

	/**
	 * @var \NxtLvlSoftware\LaravelModulesCli\Console\Extension\CommandExtension[]
	 */
	private $extensions = [];

	/**
	 * @var \NxtLvlSoftware\LaravelModulesCli\Console\Extension\CommandExtension[]
	 */
	private $resolvableExtensions = [];

	/**
	 * @var array
	 */
	private $extensionValues = [];

	/**
	 * Add an extension to the command instance.
	 *
	 * @param \NxtLvlSoftware\LaravelModulesCli\Console\Extension\CommandExtension|\NxtLvlSoftware\LaravelModulesCli\Console\Extension\CommandExtension[] $extension
	 *
	 * @return static
	 */
	public function extend($extension) : self {
		if(!is_array($extension)) {
			$extension = [$extension];
		}

		foreach($extension as $e) {
			$this->extensions[$class = get_class($e)] = $e->apply($this);

			if($e instanceof Resolvable) {
				$this->resolvableExtensions[$class] = $e;
				$this->extensionValues[$class] = null;
			}
		}

		return $this;
	}

	/**
	 * Retrieve the resolved value from an extension.
	 *
	 * @param string $class
	 *
	 * @param bool   $allowEmpty
	 *
	 * @return mixed|null
	 */
	public function extension(string $class, bool $allowEmpty = false) {
		if(!array_key_exists($class, $this->extensionValues)) { // isset returns false for null values
			throw new InvalidArgumentException("Provided command extension '{$class}' does not resolve a value");
		}

		$value = $this->extensionValues[$class];

		if(is_callable($value)) {
			$value = ($value)($this);
		}

		if($value === null and !$allowEmpty) {
			throw new InvalidExtensionValueException("Tried to retrieve invalid value for extension '{$class}'");
		}

		return $value;
	}

	/**
	 * A list of default extensions for this command class.
	 *
	 * @return CommandExtension[]
	 */
	protected function defaultExtensions() : array {
		return [];
	}

	private function resolveExtensions() : void {
		$this->extend($this->defaultExtensions());

		$resolvable = $this->resolvableExtensions;
		$this->before(static function(BaseCommand $command) use($resolvable) : void {
			foreach($resolvable as $e) {
				$command->setExtensionValue($e, $e->resolve($e->value($command)));
			}
		});
	}

	private function setExtensionValue(CommandExtension $extension, $value) : void {
		$this->extensionValues[get_class($extension)] = $value;
	}

}