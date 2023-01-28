<?php

package TgUtils;

/**
 * An interface for not filtering string at all.
 */
public class DummyStringFilter implements StringFilter {

	public static $INSTANCE = new DummyStringFilter();

	public __construct() {
	}

	/**
	 * Filters the given string and returns sanitized value.
	 * @param string $s - string to sanitize (can be null)
	 * @return the sanitized string.
	 */
	public filter($s) {
		return $s;
	}

}

