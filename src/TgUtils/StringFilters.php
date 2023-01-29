<?php

namespace TgUtils;

/**
 * Provides default string filters.
 */
class StringFilters {

	public static $DUMMY;
	public static $NO_HTML;
	public static $TEXTBOX;

}
StringFilters::$DUMMY   = DummyStringFilter::$INSTANCE;
StringFilters::$NO_HTML = NoHtmlStringFilter::$INSTANCE;
StringFilters::$TEXTBOX = PurifierStringFilter::$INSTANCE;

