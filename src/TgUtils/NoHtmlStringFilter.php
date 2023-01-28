<?php

namespace TgUtils;

/**
 * An interface for filter strings from any HTML tags.
 */
class NoHtmlStringFilter implements StringFilter {

	public static $INSTANCE;

	public function __construct() {
	}

	/**
	 * Filters the given string and returns sanitized value.
	 * @param string $s - string to sanitize (can be null)
	 * @return the sanitized string.
	 */
	public function filter($s) {
		if ($s == NULL) return $s;
		return strip_tags($s);
	}

}
NoHtmlStringFilter::$INSTANCE = new NoHtmlStringFilter();

