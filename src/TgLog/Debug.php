<?php

namespace TgLog;

/**
 * A debug message for the log.
 */
class Debug extends Message {

    /**
     * Constructs the debug.
     * @param string $message - the message text.
     */
	public function __construct($message) {
		parent::__construct('debug', $message);
	}

}