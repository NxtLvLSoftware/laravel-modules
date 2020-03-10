<?php

namespace NxtLvlSoftware\LaravelModulesCli\Console\Command;

use NxtLvlSoftware\LaravelModulesCli\Generator\FileGenerator;
use NxtLvlSoftware\LaravelModulesCli\Setting\File\NamedClassFileSettings;

class MakeModelCommand extends MakeClassCommand {

	protected $signature = "make:model {name} {--p|path=}";

	protected $description = "Create a new model.";

	public function handle() : void {
		$generator = new FileGenerator(
			$this->getModuleDisk(),
			(new NamedClassFileSettings(
				$this->makeModuleSettings(),
				"src/Model/Model.php"
			))->setName($this->name())->prependBase(false)
		);
		$generator->generate();
	}

}