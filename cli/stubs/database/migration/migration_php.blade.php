<?php
/**
 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\File\ClassFileSettings $settings
 */
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
		Schema::create("{{ $table }}", static function (Blueprint $table) : void {
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
		Schema::dropIfExists("{{ $table }}");
	}

}