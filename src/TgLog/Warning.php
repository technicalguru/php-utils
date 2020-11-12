<?php

namespace TgLog;

/**
 * A warning message for the log.
 */
class Warning extends Message {

    /**
     * Constructs the warning.
     * @param string $message - the message text.
     */
	public function __construct($message) {
		parent::__construct('warning', $message);
	}

}