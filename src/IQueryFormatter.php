<?php

namespace TotalFlex;

interface IQueryFormatter {
	/**
	 * @var MessageInfo Info message type constant
	 */
	const MessageInfo		= 1;
	/**
	 * @var MessageSuccess Success message type constant
	 */
	const MessageSuccess	= 2;
	/**
	 * @var MessageWarning Warning message type constant
	 */
	const MessageWarning	= 3;
	/**
	 * @var MessageError Error message type constant
	 */
	const MessageError		= 4;

	/**
	 * Initializes the formatter
	 *
	 * @param string $action The form action
	 * @param string $method The form method
	 */
	public function __construct($action, $method);

	/**
	 * Generate the output
	 *
	 * @return string Formatted output
	 */
	public function generate();

	/**
	 * Adds a field to the form
	 *
	 * @param string $id The field ID
	 * @param string $label Visual label to the field
	 * @param string $type HTML Input Type
	 * @param string $value (Optional) Pre-filled value. Defaults to empty.
	 */
	public function addField($id, $label, $type, $value = '');

	/**
	 * Adds a message to the query
	 *
	 * @param string $message The message
	 * @param int $type Message type.
	 * @see IQueryFormatter::Message* consts.
	 */
	public function addMessage($message, $type);
}