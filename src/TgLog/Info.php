<?php

namespace TgLog;

/**
 * An info message for the log.
 * <p>This kind of objects are mainly used for later display to users.</p>
 */
class Info extends Message {

    /**
     * Constructs the info.
     * @param string $message - the message text.
     * @param mixed  $data    - custom additional data for application specific usage.
     */
	public function __construct($message, $data = NULL) {
		parent::__construct('info', $message, $data);
	}

}
