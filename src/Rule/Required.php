<?php

namespace TotalFlex\Rule;
// use TotalFlex\IRule;

// class Required  {
class Required extends FieldAbstract implements \TotalFlex\IRule {

	protected $_contexts = 0 ;

	public function __construct ( $contexts = null ) {
		if ( $contexts === null ) $contexts = \TotalFlex\TotalFlex::CtxCreate|\TotalFlex\TotalFlex::CtxUpdate ;
		$this->_contexts = $contexts ;
	}

	/**
	 * @inheritdoc
	 */
	public function validate($value) {

		return !is_null($value) and !empty($value);

	}

}