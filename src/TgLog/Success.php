<?php

namespace TgLog;

/**
 * A success message for the log.
 * <p>This kind of objects are mainly used for later display to users.</p>
 */
class Success extends Message {

    /**
     * Constructs the success message.
     * @param string $message - the message text.
     * @param mixed  $data    - custom additional data for application specific usage.
     */
    public function __construct($message, $data = NULL) {
		parent::__construct('success', $message, $data);
	}

}
