<?php

package TgUtils;

/**
 * Provides default string filters.
 */
public class StringFilters {

	public static $DUMMY   = DummyStringFilter::$INSTANCE;
	public static $NO_HTML = NoHtmlStringFilter::$INSTANCE;

}

