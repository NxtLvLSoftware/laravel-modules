<?php
/**
 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\File\ClassFileSettings $settings
 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\File\ClassFileSettings $model
 */

use Illuminate\Support\Str;
?>
<?= '<?php' ?>


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class {{ $settings->getClassName() }} extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return(void)
	 */
	public function up() {
		Schema::create("{{ Str::of($model->getClassName())->lower()->plural() }}", static function (Blueprint $table) : void {
			$table->id();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return(void)
	 */
	public function down() {
		Schema::dropIfExists("{{ Str::of($model->getClassName())->lower()->plural() }}");
	}

}