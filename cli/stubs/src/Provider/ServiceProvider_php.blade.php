<?php
/**
 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\File\ClassFileSettings $settings
 */

use Illuminate\Support\Str;
?>
<?= '<?php' ?>


namespace {{ $settings->getNamespace() }};

use Illuminate\Support\ServiceProvider;

class {{ $settings->getClassName() }} extends ServiceProvider {

	/**
	* Register any {{ Str::lower($settings->getName()) }} services.
	*
	* @return void
	*/
	public function register() {
		//
	}

	/**
	* Bootstrap any {{ Str::lower($settings->getName()) }} services.
	*
	* @return void
	*/
	public function boot() {
		//
	}

}