<?php

namespace TotalFlex\QueryFormatter;
use TotalFlex\IQueryFormatter;

class Html implements IQueryFormatter {
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
		$form = '<form method="POST" action="' . $this->_action . '">';
		foreach ($this->_fields as list($id, $label, $type, $value)) {
			if (!empty($label)) {
				$form .= "<label for=\"$id\">$label</label>";
			}

			$form .= "<input type=\"$type\" name=\"$id\" id=\"$id\" value=\"$value\"/>";
		}
		$form .= '</form>';

		return $form;
	}

	/**
	 * @inheritdoc
	 */
	public function addField($id, $label, $type, $value = '') {
		$this->_fields[] = [$id, $label, $type, $value];
	}
}