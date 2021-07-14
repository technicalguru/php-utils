<?php

namespace TgLog;

/**
 * A log message.
 * <p>This kind of objects are mainly used for later display to users.</p>
 */
class Message {

	protected $type;
	protected $message;
	protected $data;

	public function __construct($type, $message, $data = NULL) {
		$this->type	   = $type;
		$this->message = $message;
		$this->data    = $data;
	}

	/**
	 * Returns the type of this message.
	 * @return string the type of the messae
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Returns the actual message.
	 * @return string the message text.
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Sets the custom additonal data
	 * @param mixed $data - the data.
	 */
	public function setData($data) {
		$this->data = $data;
		return $this;
	}

	/**
	 * Returns the custom additonal data
	 * @return mixed the data.
	 */
	public function getData() {
		return $this->data;
	}
}
