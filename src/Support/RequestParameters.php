<?php

namespace TotalFlex\Support;

/**
 * Class to parse all request methods (GET/POST/PUT/DELETE).
 * Thanks to Bryan Drewery
 * @see http://stackoverflow.com/a/5932067
 */
class RequestParameters {

	/**
	 * @var boolean $readedInput Indicates if already set the global variable $_PUT or $_DELETE.
	 */
	private static $readedInput = false;

	/**
	 * @var array $_params Points to the request global variable
	 */
	private $_params;

	/**
	 * Initializes the request parameters, parses and/or identify it
	 */
	public function __construct() {
		$this->_params = [];
		$this->_parseParams();
	}

  	/**
	 * Lookup request params
	 *
	 * @param string $name Name of the argument to lookup
	 * @param mixed $default Default value to return if argument is missing
	 * @return mixed The value from the GET/POST/PUT/DELETE, or $default if not set
	 */
	public function get($name, $default = null) {
		if ($this->received($name)) {
			return $this->params[$name];
		} else {
			return $default;
		}
	}

	/**
	 * Check if request parameter was received
	 * @param string $name Name of the argument to lookup
	 * @return boolean Whether or not the param was received
	 */
	public function received($name) {
		return isset($this->params[$name]);
	}

	/**
	 * Identify input parameters correct variable. If PUT or DELETE, read it from stdin first.
	 */
	private function _parseParams() {
		$method = $_SERVER['REQUEST_METHOD'];
		$globalVariable = '_' . $method;
		
		switch($method) {
			case 'PUT':
			case 'DELETE':

				if (!self::$readedInput) {
					parse_str(file_get_contents('php://input'), $this->params);
					$GLOBALS[$globalVariable] = $this->params;
					
					// Add these request vars into _REQUEST, mimicing default behavior,
					// PUT/DELETE will override existing COOKIE/GET vars
					$_REQUEST = $this->params + $_REQUEST;
				}
			
			case 'GET':	
			case 'POST':

				
				$this->params = $GLOBALS[$globalVariable];
		};
	}
}