<?php
/**
 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\File\ClassFileSettings $settings
 */
?>
<?= '<?php' ?>


namespace {{ $settings->getNamespace() }};

use Illuminate\Console\Command;

class {{ $settings->getClassName() }} extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var(string)
	 */
	protected $signature = "command:name";

	/**
	 * The console command description.
	 *
	 * @var(string)
	 */
	protected $description = "Command description";

	/**
	 * Execute the console command.
	 *
	 * @return(mixed)
	 */
	public function handle() {
		//
	}

}