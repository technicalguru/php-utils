<?php

package TgUtils;

/**
 * An interface for filter strings from any HTML tags.
 */
public class NoHtmlStringFilter implements StringFilter {

	public static $INSTANCE = new NoHtmlStringFilter();

	public __construct() {
	}

	/**
	 * Filters the given string and returns sanitized value.
	 * @param string $s - string to sanitize (can be null)
	 * @return the sanitized string.
	 */
	public filter($s) {
		if ($s == NULL) return $s;
		return strip_tags($s);
	}

}

