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
use NxtLvlSoftware\LaravelModulesCli\Console\Traits\RequiresModuleFilesystem;
use NxtLvlSoftware\LaravelModulesCli\Generator\ModuleGenerator;
use NxtLvlSoftware\LaravelModulesCli\Setting\ModuleSettings;
use RuntimeException;
use function getcwd;
use function is_file;
use function realpath;
use const DIRECTORY_SEPARATOR;

class MakeModuleCommand extends Command {
	use RequiresModuleFilesystem;

	protected $signature = "make:module {name} {--s|structure=}";

	protected $description = "Create a new module.";

	public function handle() : void {
		$name = $this->name();
		$structure = $this->structure();
		$path = getcwd() . DIRECTORY_SEPARATOR . $name;

		$generator = new ModuleGenerator(
			$this->argument("name"),
			$this->createModuleFilesystem($path),
			new ModuleSettings($path, $structure)
		);
		$generator->generate();
	}

	/**
	 * Resolve the name argument value.
	 */
	private function name() : string {
		return $this->argument("name");
	}

	/**
	 * Resolve the structure option value.
	 */
	private function structure() : ?array {
		$file = $this->option("structure");

		if($file === null) {
			return null; // not specified
		}

		$path = realpath($file);
		if($path !== false and strpos($path, "/") === 0) {
			return include $path; // absolute paths
		}

		$local = getcwd() . "/" . $file;
		if(!is_file($local)) {
			throw new RuntimeException("Could not find structure file '{$local}' in '" . getcwd() . "/'");
		}

		return include $local; // local paths
	}

}