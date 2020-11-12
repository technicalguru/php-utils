<?php

namespace TgLog;

/**
 * An info message for the log.
 */
class Info extends Message {

    /**
     * Constructs the info.
     * @param string $message - the message text.
     */
	public function __construct($message) {
		parent::__construct('info', $message);
	}

}