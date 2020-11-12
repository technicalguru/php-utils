<?php

namespace TgLog;

/**
 * An error message for the log.
 */
class Error extends Message {

    /**
     * Constructs the error.
     * @param string $message - the message text.
     */
	public function __construct($message) {
		parent::__construct('error', $message);
	}

}