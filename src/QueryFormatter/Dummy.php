<?php

namespace TotalFlex\QueryFormatter;
use TotalFlex\IQueryFormatter;

class Dummy implements IQueryFormatter {
	/**
	 * @var array The form action
	 */
	private $_action;

	/**
	 * @var array The fields to include in the actual formatting
	 */
	private $_fields;

	/**
	 * @inheritdoc
	 */
	public function __construct($action) {
		$this->_action = $action;
		$this->_fields = [];
	}

	/**
	 * @inheritdoc
	 */
	public function generate() {
		return 
			"- Create form with action pointing to " . $this->_action . "\n" .
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