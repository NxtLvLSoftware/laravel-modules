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

namespace NxtLvlSoftware\LaravelModulesCli\Console\Command;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use NxtLvlSoftware\LaravelModulesCli\Console\Traits\RequiresModuleFilesystem;
use NxtLvlSoftware\LaravelModulesCli\Generator\ModuleGenerator;
use NxtLvlSoftware\LaravelModulesCli\Setting\ModuleSettings;
use function app;
use function getcwd;
use const DIRECTORY_SEPARATOR;

class MakeModuleCommand extends Command {
	use RequiresModuleFilesystem;

	protected $signature = "make:module {name}";

	protected $description = "Create a new module.";

	public function handle() : void {
		$path = getcwd() . DIRECTORY_SEPARATOR . $this->argument("name");
		$generator = new ModuleGenerator(
			$this->argument("name"),
			$this->createModuleFilesystem($path),
			new ModuleSettings($path)
		);
		$generator->generate();
	}


}