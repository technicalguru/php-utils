<?php

namespace TgLog;

/**
 * A warning message for the log.
 * <p>This kind of objects are mainly used for later display to users.</p>
 */
class Warning extends Message {

    /**
     * Constructs the warning.
     * @param string $message - the message text.
     * @param mixed  $data    - custom additional data for application specific usage.
     */
	public function __construct($message, $data = NULL) {
		parent::__construct(Log::WARN, $message, $data);
	}

}
