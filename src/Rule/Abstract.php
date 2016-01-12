<?php

namespace TotalFlex\Rule;

class Abstract {

	protected $_contexts ;

	public function __construct ( $context ) {
		$this->$_contexts = $context ;
	}

	/**
	 * Validate value according to parameteres
	 *
	 * @param mixed $value Value to validate
	 * @return boolean Value validity
	 */
	abstract public function validate ( $value );

}