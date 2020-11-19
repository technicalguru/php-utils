<?php

namespace TgUtils;

use TgLog\Log;
use TgLog\Error;

/** Holds information about an HTTP request */
class Request {

    public const DEFAULT_REQUEST_URI = 'https://www.example.com/';
    
	/** the default instance from globals */
	protected static $request;

	/**
	 * Returns the singleton request.
	 * @return Request the request object
	 */
	public static function getRequest() {
		if (!self::$request) {
			self::$request = self::createFromGlobals();
		}
		return self::$request;
	}

	/** The URI which includes the parameters */
	public $uri;
	/** The path of the request. Does not include parameters */
	public $path;
	/** The parameters as a string */
	public $params;
	/** The language code for this request (by default: en) */
	public $langCode;
	/** The epoch time in seconds when the request was created */
	public $startTime;
	/** The post params of the request (intentionally not public) */
	protected  $postParams;
	/** The body of the request (intentionally not public) */
	protected $body;

	/** Constructor */
	public function __construct() {
        if (isset($_SERVER['REQUEST_URI'])) {
	        $this->uri    = $_SERVER['REQUEST_URI'];
        } else {
	        $this->uri    = Request::DEFAULT_REQUEST_URI;
        }
	    $uri_parts        = explode('?', $this->uri, 2);
		$this->path       = $uri_parts[0];
		$this->params     = count($uri_parts) > 1 ? $uri_parts[1] : '';
		$this->langCode   = 'en';
		$this->postParams = null;
		$this->startTime  = time();
	}

	/** Create the instance from global vars */
	public static function createFromGlobals() {
		return new Request();
	}

	/**
	 * Returns the server hostname that was requested.
	 * <p>The host is extracted from HTTP_X_FORWARDED_HOST or when not set
	 *    taken by the function getHttpHost(). Forwarded hosts return multiple
	 *    hosts eventually (e.g. when using reverse proxies). The last such
	 *    host is returned then.</p>
	 * @return string the Host requested by the user.
	 */
	public function getHost() {
		if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
			$forwarded = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
			return trim($forwarded[count($forwarded)-1]);
		}
		return $this->getHttpHost();
	}

	/**
	 * Returns the hostname as given in HTTP_HOST.
	 * @return string the HTTP_HOST variable.
	 */
	public function getHttpHost() {
		return $_SERVER['HTTP_HOST'];
	}

	/**
	 * Returns the protocol (http, https) being used by the user.
	 * <p>The protocol can be switched at reverse proxies, that's
	 *    why the HTTP_X_FORWARDED_PROTO variable is checked.
	 *    Otherwise t will be the REQUEST_SCHEME.</p>
	 * @return string the protocol as used by the user.
	 */
	public function getProtocol() {
		if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
			return $_SERVER['HTTP_X_FORWARDED_PROTO'];
		}
		return $_SERVER['REQUEST_SCHEME'];
	}

	/**
	 * Returns all path elements with .html stripped of if detected.
	 * <p>E.g. /my/path/index.html will return three elements: my, path and index.</p>
	 * @return array the path elements.
	 */
	public function getPathElements() {
		$path = substr($this->path, 1);
		if (substr($path, strlen($path)-5) == '.html') $path  = substr($path, 0, strlen($path)-5);
		if (substr($path, strlen($path)-1) == '/') $path  = substr($path, 0, strlen($path)-1);
		$elems = explode('/', $path);
		if ((count($elems) == 1) && ($elems[0] == '')) $elems = array();
		return $elems;
	}

	/**
	 * Returns whether a specific key was given as parameter.
	 * @return TRUE when parameter was set.
	 */
	public function hasGetParam($key) {
		$params = $this->getParams();
		return isset($params[$key]);
	}

	/**
	 * Returns the GET parameter value from the request.
	 * @param string $key - the parameter name
	 * @param mixed $default - the default value to return when parameter does not exist (optional, default is NULL).
	 * @return mixed the parameter value or its default.
	 */
	public function getGetParam($key, $default = NULL) {
		$params = $this->getParams();
		return isset($params[$key]) ? $params[$key] : $default;
	}

	/**
	 * Returns the parameters as an array.
	 * @return array array of parameters.
	 */
	public function getParams() {
		if ($this->paramsArray == null) {
			$this->paramsArray = self::parseQueryString($this->params);
		}
		return $this->paramsArray;
	}

	/**
	 * Returns the request method, e.g. HEAD, GET, POST.
	 * @param string the request method.
	 */
	public function getMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * Returns whether a specific key was given as parameter in POST request.
	 * @return TRUE when parameter was set.
	 */
	public function hasPostParam($key) {
		$params = $this->getPostParams();
		return isset($params[$key]);
	}

	/**
	 * Returns the POST parameter value from the request.
	 * @param string $key - the parameter name
	 * @param mixed $default - the default value to return when parameter does not exist (optional, default is NULL).
	 * @return mixed the parameter value or its default.
	 */
	public function getPostParam($key, $default = NULL) {
		$params = $this->getPostParams();
		return isset($params[$key]) ? $params[$key] : $default;
	}

	/**
	 * Returns an array of all POST parameters.
	 * @return array post parameters
	 */
	public function getPostParams() {
		if ($this->postParams == null) {
			$this->postParams = array();
			$headers = getallheaders();
			// Check that we have content-length
			if (isset($headers['Content-Length'])) {
				$len = intval($headers['Content-Length']);
				// Check that we have  a valid content-length
				if (($len>0) && ($len<10000)) {
					$this->postParams = $_POST;
				} else {
					Log::registerMessage(new Error('POST content too big'));
				}
			}
		}
		return $this->postParams;
	}

	/**
	 * Returns the body of the request (POST and PUT requests only).
	 * @return string the request body.
	 */
	public function getBody() {
		if (in_array($this->getMethod(), array('POST', 'PUT'))) {
			if ($this->body == null) {
				$this->body = file_get_contents('php://input');
			}
		}
		return $this->body;
	}

	/**
	 * Parses the query string.
	 * @param $s - the query parameter string
	 * @return array the query parameter values.
	 */
	public static function parseQueryString($s) {
		$rc = array();
		parse_str($s, $rc);
		return $rc;
	}

	/**
	 * Returns a header value.
	 * @param string $key - the header key
	 * @return string the value of the header.
	 */
	public function getHeader($key) {
		$headers = getallheaders();
		if (isset($headers[$key])) return $headers[$key];
		return null;
	}

	/**
	 * Returns a GET or POST parameter when given.
	 * <p>The method will search GET and POST parameters for the given key and return the
	 *    first it finds.</p>
	 * @param string $key - the parameter name.
	 * @param mixed $default - the default value when not found (optional, default is NULL)
	 * @param boolean $getPrecedes - TRUE when GET parameter shall be returned even when POST parameter is given. (optional, default is TRUE).
	 * @return string the parameter value or its default.
	 */
	public function getParam($key, $default = NULL, $getPrecedes = true) {
		$rc = $getPrecedes ? $this->getGetParam($key) : $this->getPostParam($key);
		if ($rc == NULL) $rc = $getPrecedes ? $this->getPostParam($key) : $this->getGetParam($key);
		if ($rc == NULL) $rc = $default;
		return $rc;
	}

	/**
	 * Returns the time since the request started.
	 * @return int elapsed time in seconds.
	 */
	public function getElapsedTime() {
		return time() - $this->startTime;
	}
}

