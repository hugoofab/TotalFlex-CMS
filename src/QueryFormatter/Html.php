<?php

namespace TotalFlex\QueryFormatter;
use TotalFlex\IQueryFormatter;

class Html implements IQueryFormatter {
	/**
	 * @var string $_action The form action
	 */
	private $_action;

	/**
	 * @var string $_method The form method
	 */
	private $_method;

	/**
	 * @var array The parsing queue
	 */
	private $_queue;

	/**
	 * @inheritdoc
	 */
	public function __construct($action, $method) {
		$this->_action = $action;
		$this->_method = $method;
		$this->_queue = [];
	}

	/**
	 * @inheritdoc
	 */
	public function generate() {
		$form = '<form method="' . $this->_method . '" action="' . $this->_action . '">';
		foreach ($this->_queue as $queueItem) {
			switch ($queueItem[0]) {
				case 'field':
					$form .= $this->_generateField(
						$queueItem[1],
						$queueItem[2],
						$queueItem[3],
						$queueItem[4]
					);
					break;
				
				case 'message':
					$form .= $this->_generateMessage(
						$queueItem[1],
						$queueItem[2]
					);
					break;
			}
		}
		$form .= '</form>';

		return $form;
	}

	/**
	 * @inheritdoc
	 */
	public function addField($id, $label, $type, $value = '') {
		$this->_queue[] = ['field', $id, $label, $type, $value];
	}

	/**
	 * @inheritdoc
	 */
	public function addMessage($message, $type) {
		$this->_queue[] = ['message', $type];	
	}

	/**
	 * Generate field HTML.
	 *
	 * @param string $id The field ID
	 * @param string $label Visual label to the field
	 * @param string $type HTML Input Type
	 * @param string $value Pre-filled value.
	 * @return string The field HTML
	 */
	protected function _generateField($id, $label, $type, $value) {
		$output = "";
		
		if (!empty($label)) {
			$output .= "<label for=\"$id\">$label</label>";
		}

		$output .= "<input type=\"$type\" name=\"$id\" id=\"$id\" value=\"$value\"/>";

		return $output;
	}

	/**
	 * Generate message HTML
	 *
	 * @param string $message The message
	 * @param int $type Message type.
	 * @return string The message HTML
	 */
	protected function _generateMessage($message, $type) {
		return "<div class=\"msg msg-$type\">$message</div>";
	}
}
