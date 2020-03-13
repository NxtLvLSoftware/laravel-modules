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

namespace NxtLvlSoftware\LaravelModulesCli\Console\Extension;

use Illuminate\Console\Command;
use NxtLvlSoftware\LaravelModulesCli\Console\Command\BaseCommand;
use NxtLvlSoftware\LaravelModulesCli\Console\Traits\RequiresModuleSettings;
use NxtLvlSoftware\LaravelModulesCli\Contract\Console\Extension\Resolvable;
use NxtLvlSoftware\LaravelModulesCli\Setting\FileSettings as Settings;
use function is_a;

class FileSettings extends CommandExtension implements Resolvable {
	use RequiresModuleSettings;

	/**
	 * @var string
	 */
	private $template;

	/**
	 * @var string|null
	 */
	private $class;

	/**
	 * @var callable
	 */
	private $nameResolver;

	/**
	 * @var bool|null
	 */
	private $resolved = null;

	public function __construct(string $template, ?string $class, callable $nameResolver = null) {
		if($class === null or !is_a($class, Settings::class, true)) {
			$class = Settings::class;
		}

		$this->template = $template;
		$this->class = $class;
		$this->nameResolver = $nameResolver;
	}

	/**
	 * @inheritDoc
	 */
	public function value(Command $command) {
		return new $this->class($this->makeModuleSettings(), $this->template);
	}

	/**
	 * @inheritDoc
	 */
	public function resolve($input) {
		/** @var $input \NxtLvlSoftware\LaravelModulesCli\Setting\FileSettings */
		$resolver = $this->nameResolver;
		$resolved = &$this->resolved;
		if($resolver !== null) {
			return static function(BaseCommand $command) use($input, $resolver, &$resolved) : Settings {
				if($resolved === null) {
					$resolved = true; // locking var to prevent the resolver callback invoking itself recursively
					$input
						->setName(($resolver)($command)); // only resolve the value once
				}

				return $input;
			};
		}

		return $input;
	}

	public static function valueFor(BaseCommand $command) : Settings {
		return $command->extension(static::class);
	}

}