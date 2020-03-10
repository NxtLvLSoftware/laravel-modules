<?php

declare(strict_types=1);

/**
 * Copyright (C) 2020 NxtLvL Software Solutions
 *
 * @author    Jack Noordhuis <me@jacknoordhuis.net>
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

namespace NxtLvlSoftware\LaravelModulesCli\Setting\File;

use Illuminate\Contracts\Filesystem\Filesystem;
use RuntimeException;
use function data_get;
use function explode;
use function get_current_user;
use function strtolower;
use function trim;

class ComposerJsonFileSettings extends JsonFileSettings {

	/**
	 * @var string
	 */
	private $vendor;

	/**
	 * @var string
	 */
	private $package;

	/**
	 * @var string|null
	 */
	private $description = "My new laravel module.";

	/**
	 * @var string|null
	 */
	private $type = "library";

	/**
	 * @var string|null
	 */
	private $license = "MIT";

	/**
	 * @var array
	 */
	private $require = [
		"laravel/framework" => "^7.0"
	];

	/**
	 * @var array
	 */
	private $autoload = [
		"psr-4" => []
	];

	/**
	 * @var string|null
	 */
	private $minimumStability = "dev";

	/**
	 * @var bool|null
	 */
	private $preferStable = true;

	/**
	 * @var string|null
	 */
	private $cachedNs = null;

	protected function init() : void {
		if($this->getModuleSettings() === null) {
			return;
		}

		$this->vendor = strtolower(get_current_user());
		$this->package = strtolower($this->getModuleSettings()->getName());
		$this->autoload["psr-4"][$this->getModuleSettings()->getNamespace() . "\\"] = "src";
	}

	public function getVendor() : string {
		return $this->vendor;
	}

	public function setVendor(string $vendor) : void {
		$this->vendor = $vendor;
	}

	public function getPackage() : string {
		return $this->package;
	}

	public function setPackage(string $package) : void {
		$this->package = $package;
	}

	public function getDescription() : ?string {
		return $this->description;
	}

	public function setDescription(?string $description) : void {
		$this->description = $description;
	}

	public function getType() : ?string {
		return $this->type;
	}

	public function setType(?string $type) : void {
		$this->type = $type;
	}

	public function getLicense() : ?string {
		return $this->license;
	}

	public function setLicense(?string $license) : void {
		$this->license = $license;
	}

	public function getRequire() : array {
		return $this->require;
	}

	public function setRequire(array $require) : void {
		$this->require = $require;
	}

	public function getAutoload() : array {
		return $this->autoload;
	}

	public function setAutoload(array $autoload) : void {
		$this->autoload = $autoload;
	}

	public function getMinimumStability() : ?string {
		return $this->minimumStability;
	}

	public function setMinimumStability(?string $minimumStability) : void {
		$this->minimumStability = $minimumStability;
	}

	public function getPreferStable() : ?bool {
		return $this->preferStable;
	}

	public function setPreferStable(?bool $preferStable) : void {
		$this->preferStable = $preferStable;
	}

	public function fromFile(Filesystem $filesystem) : void {
		$this->fromJson($filesystem->get("composer.json"));
		$this->syncFromData();
	}

	public function toFile(Filesystem $filesystem) : void {
		$this->syncToData();
		$filesystem->put("composer.json", $this->toJson());
	}

	/**
	 * Write the json data array fields in the property fields.
	 */
	public function syncFromData() : void {
		$nameParts = explode("/", $this->data["name"]);
		$this->vendor = $nameParts[0];
		$this->package = $nameParts[1];

		$this->description = $this->data["description"] ?? null;
		$this->type = $this->data["type"] ?? null;
		$this->license = $this->data["license"] ?? null;
		$this->require = $this->data["require"] ?? [];
		$this->autoload = $this->data["autoload"] ?? [];
		$this->minimumStability = $this->data["minimum-stability"] ?? null;
		$this->preferStable = $this->data["prefer-stable"] ?? null;
	}

	/**
	 * Write the property fields into the json data array.
	 */
	public function syncToData() : void {
		$this->data["name"] = $this->vendor . "/" . $this->package;

		if($this->description !== null) {
			$this->data["description"] = $this->description;
		}

		if($this->type !== null) {
			$this->data["type"] = $this->type;
		}

		if($this->license !== null) {
			$this->data["license"] = $this->license;
		}

		$this->data["require"] = $this->require;
		$this->data["autoload"] = $this->autoload;

		if($this->minimumStability !== null) {
			$this->data["minimum-stability"] = $this->minimumStability;
		}

		if($this->preferStable !== null) {
			$this->data["prefer-stable"] = $this->preferStable;
		}
	}

	/**
	 * Attempt to detect the namespace of a module from the composer.json file.
	 */
	public function detectNamespace() : string {
		if ($this->cachedNs !== null) {
			return $this->cachedNs;
		}

		foreach ((array) data_get($this->data, "autoload.psr-4") as $namespace => $path) {
			foreach ((array) $path as $pathChoice) {
				if (rtrim(trim($pathChoice, "/"), "/") === "src") {
					return $this->cachedNs = trim($namespace, "\\");
				}
			}
		}

		throw new RuntimeException("Unable to detect application namespace.");
	}

}