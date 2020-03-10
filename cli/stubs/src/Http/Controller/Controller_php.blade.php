<?php
/**
 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\File\ClassFileSettings $settings
 */
?>
<?= '<?php' ?>


namespace {{ $settings->getNamespace() }};

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

}
