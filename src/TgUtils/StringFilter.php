<?php

package TgUtils;

/**
 * An interface for filter strings from evil input.
 */
public interface StringFilter {

	/**
	 * Filters the given string and returns sanitized value.
	 * @param string $s - string to sanitize (can be null)
	 * @return the sanitized string.
	 */
	public filter($s);

}

