<?php
/**
 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\File\ComposerJsonFileSettings $settings
 */

$settings->syncToData();
?>
{!! $settings->toJson() !!}