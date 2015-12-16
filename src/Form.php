<?php

namespace TotalFlex;

class Form {

	/**
	 * @var string $_action The form action
	 */
	private $_action = "";

	/**
	 * @var string $_method The form method
	 */
	private $_method = "POST" ;

	private $_enctype = "";


	public function __construct ( ) {

	}

	public function getAction ( ) {
		return $this->_action ;
	}

	public function setAction ( $action ) {
		$this->_action = $action;
		pr($action);
	}

	public function getMethod ( ) {
		return $this->_method ;
	}

	public function setMethod ( $method ) {
		$this->_method = $method;
	}

	public function getEnctype ( ) {
		return $this->_enctype ;
	}

	public function setEnctype ( $enctype ) {
		$this->_enctype = $enctype;
	}




}