<?php

namespace TgUtils;

/**
 * An interface for filter strings from any HTML tags.
 */
class NoHtmlStringFilter extends AbstractStringFilter {

	public static $INSTANCE;

	public function __construct() {
		parent::__construct();
	}

	protected function filterString($s) {
		return strip_tags($s);
	}
}
NoHtmlStringFilter::$INSTANCE = new NoHtmlStringFilter();

