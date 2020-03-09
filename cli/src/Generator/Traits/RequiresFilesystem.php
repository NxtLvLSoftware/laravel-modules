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

trait RequiresFilesystem {

	/**
	 * @var \Illuminate\Contracts\Filesystem\Filesystem
	 */
	private $filesystem;

	public function getFilesystem() : Filesystem {
		return $this->filesystem;
	}

}