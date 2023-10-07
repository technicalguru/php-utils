<?php

namespace TgUtils\Auth;

class DefaultCredentialsProvider implements CredentialsProvider {
 
    private $credentials;
    
	public function __construct($username, $password) {
		$this->credentials = array(
			'username' => $username,
			'password' => $password
		);
    }
    
	/**
	  * Returns the username.
	  * @return string the username
	  */
    public function getUsername(){
        return $this->get('username');
    }

	/**
	  * Returns the password.
	  * @return string the password
	  */
    public function getPassword() {
        return $this->get('password');
    }

	/**
	  * Returns the credentials of the given key.
	  * @param string  $key - the key of the credentials.
	  * @return string the credentials stored of NULL
	  */
	public function get($key) {
		if (isset($this->credentials[$key])) {
			return $this->credentials[$key];
		}
		return NULL;
	}

	/**
	  * Sets the credentials of the given key.
	  * @param string $key   - the key of the credentials.
	  * @param string $value - credentials to be stored at this key
	  */
	protected function set($key, $value) {
		$this->credentials[$key] = $value;
	}
}
