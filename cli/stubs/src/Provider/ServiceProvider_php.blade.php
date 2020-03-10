<?php
/**
 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\File\NamedClassFileSettings $settings
 */

use Illuminate\Support\Str;
?>
<?= '<?php' ?>


namespace {{ $settings->getNamespace() }};

use Illuminate\Support\ServiceProvider;

class {{ $settings->getName() }}ServiceProvider extends ServiceProvider {

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