<?php

namespace TgUtils;

/**
 * Abstract string filter that traverses objects and arrays.
 */
abstract class AbstractStringFilter implements StringFilter {

	public function __construct() {
	}

	/**
	 * Filters the given string and returns sanitized value.
	 * @param string $s - string to sanitize (can be null)
	 * @return the sanitized string.
	 */
	public function filter($s) {
		if ($s == NULL) return $s;
		if (is_string($s)) {
			return $this->filterString($s);
		} else if (is_array($s)) {
			foreach ($s AS $key => $value) {
				$s[$key] = $this->filter($value);
			}
		} else if (is_object($s)) {
			foreach (get_object_vars($s) AS $name => $value) {
				$s->$name = $this->filter($value);
			}
		}
		return $s;
	}

	protected function filterString($s) {
		return $s;
	}
}

