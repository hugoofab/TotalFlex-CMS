<?php

namespace TotalFlex\View\Formatter;

interface ViewFormatterInterface {
	
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
	 * Generate the output
	 *
	 * @return string Formatted output
	 */
	public static function generate ( \TotalFlex\View $View , $context );

	/**
	 * Adds a field to the form
	 *
	 * @param string $id The field ID
	 * @param string $label Visual label to the field
	 * @param string $type HTML Input Type
	 * @param string $value (Optional) Pre-filled value. Defaults to empty.
	 */
	// public function addField ( $id, $label, $type, $value = '');

	/**
	 * Adds a message to the query
	 *
	 * @param string $message The message
	 * @param int $type Message type.
	 * @see IQueryFormatter::Message* consts.
	 */
	// public function addMessage($message, $type);

}