<?php

namespace TotalFlex\QueryFormatter;
use TotalFlex\IQueryFormatter;

class Dummy implements IQueryFormatter {
	/**
	 * @var string $_action The form action
	 */
	private $_action;

	/**
	 * @var string $_method The form method
	 */
	private $_method;

	/**
	 * @var array The fields to include in the actual formatting
	 */
	private $_fields;

	/**
	 * @inheritdoc
	 */
	public function __construct($action, $method) {
		$this->_action = $action;
		$this->_method = $method;
		$this->_fields = [];
	}

	/**
	 * @inheritdoc
	 */
	public function generate() {
		return 
			"- Create a " . $this->_method . " form with action pointing to " . $this->_action . "\n" .
			"\t" . implode("\n\t", $this->_fields)
		;
	}

	/**
	 * @inheritdoc
	 */
	public function addField($id, $label, $type, $value = '') {
		$this->_fields[] = 
			"- Add field called '" . $id . 
			"', of type '" . $type . 
			"', with '" . $label . 
			"' label and with value '" . $value . 
			"'";
	}
}