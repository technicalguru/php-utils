<?php

namespace TgLog;

/**
 * Handles various logging requirements such as:
 * <ul>
 * <li>registering messages in session to be available in next HTTP call.</li>
 * <li>logging messages to error_log</li>
 * <li>collecting log messages so they can be retrieved later for user display.</li>
 * </ul>
 */
class Log {
    
    /** The available log priorities */
    protected static $logPriorities;
    /** The log level to issue in error_log */
	protected static $logLevel;
	
	/** The single instance of the log */
	public    static $instance;
	
	/** All log messages that were issued */
	public    $messages;
	
	/**
	 * Returns the single instance.
	 * @return Log - single Log instance 
	 */
    public static function instance() {
		if (self::$logLevel == null) {
			self::$logLevel == 'info';
		}
		if (self::$instance == null) {
			self::$instance = new Log();
		}
		return self::$instance;
	}
	
	/**
	 * Public constructor.
	 * <p>Usually you don't need to instantiate it yourself but use the single instance.</p> 
	 */
    public function __construct() {
		$this->messages = array();
	}

	/**
	 * Logs the given message and, optionally, object with given severity.
	 * @param string $sev - the severity
	 * @param string $s - the log message
	 * @param mixed $o - an object to be logged along with the message.
	 */
	protected function log($sev, $s, $o = null) {
		if (!is_string($s)) $s = json_encode($s, JSON_PRETTY_PRINT);
		if ($o != null) {
			if ($o instanceof \Exception) {
				$s .= get_class($o).' "'.$o->getCode().' - '.$o->getMessage()."\" at\n".$o->getTraceAsString();
			} else {
				$s .= json_encode($o, JSON_PRETTY_PRINT);
			}
		}
		$this->messages[$sev][] = $s;
		if (self::isLogLevelIncluded($sev)) error_log('[WebApp - '.strtoupper($sev).'] '.$s);
	}

	/**
	 * Logs the stacktrace, optionally excluding a certain file in the trace.
	 * @param string $sev - severity to be logged
	 * @param string $excludeFile - do not include this file in stacktrace.
	 */
	public function logStackTrace($sev, $excludeFile = NULL) {
		$trace = $this->getStackTrace($excludeFile);
		$this->log($sev, 'Stacktrace:');
		foreach ($trace AS $idx => $line) {
			$this->log($sev, '   '.$line);
		}
	}

	/**
	 * Returns the stacktrace, optionally excluding a certain file in the trace.
	 * @param string $excludeFile - do not include this file in stacktrace.
	 * @return array list of trace messages for each call in stack
	 */
	public function getStackTrace($excludeFile = NULL) {
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$arr = array();
		foreach ($backtrace AS $step) {
			if (($step['file'] != __FILE__) && (($excludeFile == NULL) || ($step['file'] != $excludeFile))) {
				if ($step['type']) {
					$arr[] = 'at '.$step['file'].' (line '.$step['line'].'): '.$step['class'].$step['type'].$step['function'].'()';
				} else {
					$arr[] = 'at '.$step['file'].'(line '.$step['line'].'): '.$step['function'].'()';
				}
			}
		}
		return $arr;
	}

	/**
	 * Debug message into log.
	 * @param string $s - the message
	 * @param mixed  $o - an oject to be dumped along with message. An Exception object will cause a stacktrace dump (optional).
	 */
	public static function debug($s, $o = null) {
		self::instance()->log('debug', $s, $o);
	}

	/**
	 * Info message into log.
	 * @param string $s - the message
	 * @param mixed  $o - an oject to be dumped along with message. An Exception object will cause a stacktrace dump (optional).
	 */
	public static function info($s, $o = null) {
		self::instance()->log('info', $s, $o);
	}

	/**
	 * Warning message into log.
	 * @param string $s - the message
	 * @param mixed  $o - an oject to be dumped along with message. An Exception object will cause a stacktrace dump (optional).
	 */
	public static function warn($s, $o = null) {
		self::instance()->log('warn', $s, $o);
	}

	/**
	 * Error message into log.
	 * @param string $s - the message
	 * @param mixed  $o - an oject to be dumped along with message. An Exception object will cause a stacktrace dump (optional).
	 */
	public static function error($s, $o = null) {
		self::instance()->log('error', $s, $o);
	}

	/**
	 * Print stacktrace into debug log, optionally excluding a certain file from trace.
	 * @param string $excludeFile - the file to be excluded (optional).
	 */
	public static function debugStackTrace($excludeFile = NULL) {
		self::instance()->logStackTrace('debug', $excludeFile);
	}

	/**
	 * Print stacktrace into info log, optionally excluding a certain file from trace.
	 * @param string $excludeFile - the file to be excluded (optional).
	 */
	public static function infoStackTrace($excludeFile = NULL) {
		self::instance()->logStackTrace('info', $excludeFile);
	}

	/**
	 * Print stacktrace into warning log, optionally excluding a certain file from trace.
	 * @param string $excludeFile - the file to be excluded (optional).
	 */
	public static function warnStackTrace($excludeFile = NULL) {
		self::instance()->logStackTrace('warn', $excludeFile);
	}

	/**
	 * Print stacktrace into error log, optionally excluding a certain file from trace.
	 * @param string $excludeFile - the file to be excluded (optional).
	 */
	public static function errorStackTrace($excludeFile = NULL) {
		self::instance()->logStackTrace('error', $excludeFile);
	}

	/**
	 * Set the global log level.
	 * @param $sev - shall be debug, info, warn, error or none.
	 */
	public static function setLogLevel($sev) {
		self::$logLevel = $sev;
	}
	
	/**
	 * Register a message for session-wide using.
	 * <p>The message will sent to the log and also stored in session for further usage.</p>
	 * @param Message $message - the message to be registered.
	 */
    public static function register(Message $message) {
		$_SESSION['messages'][] = $message;
		switch ($message->getType()) {
		case 'error':   self::error($message->getMessage()); break;
		case 'warning': self::warn($message->getMessage()); break;
		case 'info':    self::info($message->getMessage()); break;
		case 'debug':   self::debug($message->getMessage()); break;
		}
	}

	/**
	 * Clean all session-wide registsred messages, e.g. after display to user.
	 */
	public static function clean() {
		$_SESSION['messages'] = array();
	}

	/**
	 * Get all session wide messages that were registered, e.g. for user display.
	 * @return array list of messages registered.
	 */
	public static function get() {
		return $_SESSION['messages'];
	}
	
	/**
	 * Internal function to check whether a loglevel is supposed to be logged.
	 * @param string $sev - the log level to check
	 * @return TRUE when the current loglevel allows logging.
	 */
	protected static function isLogLevelIncluded($sev) {
		if (self::$logPriorities == null) {
			self::$logPriorities = array('none', 'debug', 'info', 'warning', 'error');
		}
		$logIndex = array_search(self::$logLevel, self::$logPriorities);
		$sevIndex = array_search($sev, self::$logPriorities);
		return $logIndex <= $sevIndex;
	}

	
}
