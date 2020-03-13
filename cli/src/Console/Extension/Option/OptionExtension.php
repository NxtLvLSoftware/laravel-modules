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

namespace NxtLvlSoftware\LaravelModulesCli\Console\Extension\Option;

use Illuminate\Console\Command;
use InvalidArgumentException;
use NxtLvlSoftware\LaravelModulesCli\Console\Command\BaseCommand;
use NxtLvlSoftware\LaravelModulesCli\Console\Extension\CommandExtension;
use NxtLvlSoftware\LaravelModulesCli\Contract\Console\Extension\Resolvable;
use Symfony\Component\Console\Input\InputOption;
use function is_string;

/**
 * Base class for a command option extension.
 */
abstract class OptionExtension extends CommandExtension implements Resolvable {

	/**
	 * @var \Symfony\Component\Console\Input\InputArgument
	 */
	private $option;

	public function __construct($option) {
		if($option instanceof InputOption) {
			$this->option = $option;
		} elseif(is_string($option)) {
			$this->option = self::parseOption($option);
		} else {
			throw new InvalidArgumentException("Unknown option type provided");
		}
	}

	protected function onApply(BaseCommand $command) : void {
		$command->getDefinition()->addOption($this->option);
	}

	/**
	 * @inheritDoc
	 */
	final public function value(Command $command) {
		return $command->option($this->option->getName());
	}

}