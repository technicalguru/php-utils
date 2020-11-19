<?php

namespace TgUtils;

use \TgI18n\I18N;

I18N::addI18nFile(__DIR__ . '/../utils_i18n.php', FALSE);

/**
 * Provides some formatting utils.
 * @author ralph
 *        
 */
class FormatUtils {

	/**
	 * Format a price value using localization.
	 * @param float $value - the value
	 * @param string $currency - the currency
	 * @param string $language - the language code (optional, default language of I18N class).
	 * @param string $spaceChar - the space character to be used between value and currency (optional, default is HTML non-breaking space).
	 * @return string the formatted price in localized manner.
	 */
    public static function formatPrice($value, $currency, $language = null, $spaceChar = '&nbsp;') {
		return number_format(floatval($value), 2, I18N::_('decimal_point', $language), I18N::_('thousand_sep', $language)).$spaceChar.$currency;
	}
	
	/**
	 * Format a unit by using prefixes (decimal and computer bytes only).
	 * @param int $size - the size to be formatted
	 * @param string $unit - the unit string
	 * @param int $precision - how many digits after decimal point shall be displayed (optional, default is 1)
	 * @param string $language - the language to be used for number formatting separators (optional, default is NULL)
	 * @param bool $bytes - TRUE when computer byte counting is used, FALSE when decimal base shall be applied (optional, default is TRUE)
	 * @return string a formatted string
	 */
	public static function formatUnit($size, $unit, $precision = 1, $language = NULL, $bytes = TRUE) {
	    $prefixes = $bytes ? array('K', 'M', 'G', 'T' ) : array('k', 'M', 'G', 'T');  
	    $rc       = $unit;
	    $base     = $bytes ? 1024 : 1000;
		// Only until GB
		if ($size > $base) {
			$size = $size/$base;
			$rc   = $prefixes[0].$unit;
		}
		if ($size > $base) {
			$size = $size/$base;
			$rc   = $prefixes[1].$unit;
		}
		if ($size > $base) {
			$size = $size/$base;
			$rc   = $prefixes[2].$unit;
		}
		if ($size > $base) {
			$size = $size/$base;
			$rc   = $prefixes[3].$unit;
		}
		$size = $rc != $unit ? number_format($size, $precision, I18N::_('decimal_point', $language), I18N::_('thousand_sep', $language)) : number_format($size, 0, I18N::_('decimal_point', $language), I18N::_('thousand_sep', $language));
		return $size.' '.$rc;
	}
}

