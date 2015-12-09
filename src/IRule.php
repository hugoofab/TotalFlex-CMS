<?php

namespace TotalFlex;

interface IRule {
	/**
	 * Validate value according to parameteres
	 *
	 * @param mixed $value Value to validate
	 * @return boolean Value validity
	 */
	public function validate($value);
}