<?php

declare(strict_types=1);

/**
 * Copyright (C) 2020 NxtLvL Software Solutions
 *
 * @author Jack Noordhuis <me@jacknoordhuis.net>
 * @copyright NxtLvL Software Solutions
 *
 * This is free and unencumbered software released into the public domain.
 *
 * Anyone is free to copy, modify, publish, use, compile, sell, or
 * distribute this software, either in source code form or as a compiled
 * binary, for any purpose, commercial or non-commercial, and by any means.
 *
 * In jurisdictions that recognize copyright laws, the author or authors
 * of this software dedicate any and all copyright interest in the
 * software to the public domain. We make this dedication for the benefit
 * of the public at large and to the detriment of our heirs and
 * successors. We intend this dedication to be an overt act of
 * relinquishment in perpetuity of all present and future rights to this
 * software under copyright law.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * For more information, please refer to <http://unlicense.org/>
 *
 */

namespace NxtLvlSoftware\LaravelModulesCli\Setting;

use Illuminate\Contracts\View\View as ViewInstance;
use Illuminate\Support\Facades\View;
use function basename;
use function dirname;
use function str_replace;
use function trim;

class FileSettings {

	/**
	 * @var string
	 */
	private $template;

	/**
	 * @var string
	 */
	private $output;

	/**
	 * @var \NxtLvlSoftware\LaravelModulesCli\Setting\ModuleSettings
	 */
	private $moduleSettings;

	/**
	 * Constructor is final to prevent modifying the signature. Use @link init() to
	 * perform actions during construction.
	 */
	final public function __construct(ModuleSettings $moduleSettings, string $path) {
		$this->moduleSettings = $moduleSettings;
		$this->output = $path;
		$this->template = $this->resolveTemplateName();

		$this->init();
	}

	/**
	 *
	 */
	protected function init() : void {
		//
	}

	public function getModuleSettings() : ModuleSettings {
		return $this->moduleSettings;
	}

	public function getView() : ViewInstance {
		return View::make($this->template, [
			"settings" => $this
		]);
	}

	public function getTemplate() : string {
		return $this->template;
	}

	public function getOutput() : string {
		return $this->output;
	}

	/**
	 * Get a stub filename from the file path relative to the module project.
	 */
	private function resolveTemplateName() : string {
		$name = str_replace(".", "_", trim(basename($this->output), ".")); // replace file extension separator '.' with '_'
		$base = str_replace("/", ".", trim(dirname($this->output), "/")); // replace path separators '/' with '.'

		return $base . "." . $name;
	}

}