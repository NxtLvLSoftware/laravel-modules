<?php
/**
 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\File\NamedClassFileSettings $settings
 */
?>
<?= '<?php' ?>


namespace {{ $settings->getNamespace() }};

use Illuminate\Console\Command;

class {{ $settings->getName() }}Command extends Command {

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