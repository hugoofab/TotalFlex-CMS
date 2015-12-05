<?php

namespace TotalFlex\Rule;
use TotalFlex\IRule;

class Required implements IRule {
	/**
	 * @inheritdoc
	 */
	public function validate($value) {
		return !is_null($value) and !empty($value);
	}
}