<?php

namespace TgUtils;

/**
 * An interface for not filtering string at all.
 */
class DummyStringFilter implements StringFilter {

	public static $INSTANCE;

	public function __construct() {
	}

	/**
	 * Filters the given string and returns sanitized value.
	 * @param string $s - string to sanitize (can be null)
	 * @return the sanitized string.
	 */
	public function filter($s) {
		return $s;
	}

}
DummyStringFilter::$INSTANCE = new DummyStringFilter();
