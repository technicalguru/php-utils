<?php

namespace TgUtils;

/**
 * An interface for filter strings from evil input.
 */
interface StringFilter {

	/**
	 * Filters the given string and returns sanitized value.
	 * @param string $s - string to sanitize (can be null)
	 * @return the sanitized string.
	 */
	public function filter($s);

}

