<?php

namespace TotalFlex;

interface IQueryFormatter {
	/**
	 * Initializes the formatter
	 *
	 * @param string $action The form action
	 */
	public function __construct($action);

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
}