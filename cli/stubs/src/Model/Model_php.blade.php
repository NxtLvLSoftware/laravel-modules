<?php
/**
 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\File\NamedClassFileSettings $settings
 */
?>
<?= '<?php' ?>


namespace {{ $settings->getNamespace() }};

use Illuminate\Database\Eloquent\Model;

class {{ $settings->getName() }} extends Model {

	/**
	 * The attributes that should be cast.
	 *
	 * @var(array)
	 */
	protected $casts = [
		//
	];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var(bool)
	 */
	public $timestamps = true;

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var(array)
	 */
	protected $hidden = [
		//
	];

}