<?php
/**
 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\FileSettings $settings
 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\File\ClassFileSettings $model
 */
?>
<?= '<?php' ?>


/** @var \Illuminate\Database\Eloquent\Factory $factory */

use {{ $model->getFqn() }};
use Faker\Generator as Faker;

$factory->define({{ $model->getClassName() }}::class, static function(Faker $faker) : array {
	return [
		//
	];
});