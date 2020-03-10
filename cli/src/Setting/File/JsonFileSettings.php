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

use NxtLvlSoftware\LaravelModulesCli\Setting\FileSettings;
use function in_array;
use function is_int;
use function is_iterable;
use function json_decode;
use function json_encode;
use const JSON_THROW_ON_ERROR;

class JsonFileSettings extends FileSettings {

	/**
	 * @var array
	 */
	protected $data;

	public function fromJson(string $data) : void {
		$this->data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
	}

	public function toJson() : string {
		return json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
	}

	/**
	 * Merge an array of data with the current data. By default no values will be overwritten or duplicated.
	 *
	 * @param array      $data     The data to set.
	 * @param bool       $override If the values should be overwritten.
	 * @param array|null $nested   Nested data value, only internally when the method is called recursively.
	 */
	public function merge(array $data, bool $override = false, array &$nested = null) : void {
		if($nested === null) {
			$nested = &$this->data; // set to a reference of the current data
		}

		foreach($data as $key => $value) {
			if(is_iterable($value)) {
				if(!isset($nested[$key])) {
					$nested[$key] = []; // make sure empty arrays/objects exist in case we need to set children
				}

				$this->merge($value, $override, $nested[$key]); // call method recursively to set children
				continue;
			}

			if(is_int($key) and !in_array($value, $nested, true)) { // value is adding to a simple array, add if value doesn't already exist
				$nested[] = &$value; // add to an array
				continue;
			}

			if(isset($nested[$key]) and !$override) { // value is an object property and it already exists, skip if we aren't overwriting
				continue;
			}

			$nested[$key] = $value; // set the value
		}
	}

}