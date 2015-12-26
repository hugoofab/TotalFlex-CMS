<?php

namespace TotalFlex\Rule;
use TotalFlex\IRule;

class Length implements IRule {
	/**
	 * @var int $_min Min length
	 */
	private $_min;

	/**
	 * @var int $_max Max length
	 */
	private $_max;

	/**
	 * Initializes this rule
	 *
	 * @param int|null $min Min length
	 * @param int|null $max Max length
	 * @throws \InvalidArgumentException
	 */
	public function __construct($min, $max) {
		if (!(is_int($min) or is_null($min))) {
			throw new \InvalidArgumentException('Expected int or null to min length');
		}

		$this->_min = $min;

		if (!(is_int($max) or is_null($max))) {
			throw new \InvalidArgumentException('Expected int or null to max length');
		}

		$this->_max = $max;
	}

	/**
	 * @inheritdoc
	 */
	public function validate($value) {
		if (is_array($value)) {
			return $this->_validateLength(count($value));
		} elseif (is_string($value)) {
			return $this->_validateLength(strlen($value));
		} else {
			return false;
		}
	}

	/**
	 * Actual length verify
	 *
	 * @param int $length Object's length
	 * @return boolean Length validity
	 */
	private function _validateLength($length) {
		if (!is_null($this->_min) and ($length < $this->_min)) {
			return false;
		}

		if (!is_null($this->_max) and ($length > $this->_max)) {
			return false;
		}

		return true;
	}
}