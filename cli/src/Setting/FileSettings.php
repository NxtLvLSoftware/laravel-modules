<?php

declare(strict_types=1);

/**
 * Copyright (C) 2018–2020 NxtLvL Software Solutions
 *
 * This is private software, you cannot redistribute and/or modify it in any way
 * unless given explicit permission to do so. If you have not been given explicit
 * permission to view or modify this software you should take the appropriate actions
 * to remove this software from your device immediately.
 *
 * @author Jack Noordhuis
 *
 */

namespace NxtLvlSoftware\LaravelModulesCli\Setting;

use Illuminate\Contracts\View\View as ViewInstance;
use Illuminate\Support\Facades\View;

class FileSettings {

	/**
	 * @var string
	 */
	private $template;

	/**
	 * @var string
	 */
	private $output;

	public function __construct(string $template, string $output) {
		$this->template = $template;
		$this->output = $output;
	}

	public function getTemplate() : string {
		return $this->template;
	}

	public function getView() : ViewInstance {
		return View::make($this->template, [
			"settings" => $this
		]);
	}

	public function getOutput() : string {
		return $this->output;
	}

}