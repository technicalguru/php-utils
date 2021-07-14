<?php

namespace TgLog;

/**
 * An error message for the log.
 * <p>This kind of objects are mainly used for later display to users.</p>
 */
class Error extends Message {

    /**
     * Constructs the error.
     * @param string $message - the message text.
     * @param mixed  $data    - custom additional data for application specific usage.
     */
	public function __construct($message, $data = NULL) {
		parent::__construct(Log::ERROR, $message, $data);
	}

}
