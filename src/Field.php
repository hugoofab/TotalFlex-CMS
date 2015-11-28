<?php

namespace TotalFlex;

class Field { 
	/**
	 * @var string Column name
	 */
	private $_column;

	/**
	 * @var string Field label
	 */
	private $_label;

    /**
     * @var string HTML Input type
     */
    private $_type;

	/**
	 * @var boolean Is a primary key
	 */
	private $_primaryKey;

	/**
	 * @var array[Context] The contexts this fields is allowed
	 */
	private $_contexts;

	/**
	 * @var array[IRule] Rules to this field
	 */
	private $_rules;

	/**
	 * @var TotalFlex\Table This field's table
	 */
	private $_table;

	/**
	 * Constructs the field
	 *
	 * @param string $column Field column name
	 * @param TotalFlex\Table $table (Optional) This field's table
	 * @throws \InvalidArgumentException
	 */
	public function __construct($column, $table = null) {
		$this
            ->setColumn($column)
            ->setLabel($column)
            ->setType('text')
            ->setPrimaryKey(false)
            ->setContexts(TotalFlex::CtxNone)
            ->setRules([]);
		
		if ($table instanceof Table) {
			$this->_table = $table;
		} else {
			throw new \InvalidArgumentException('Expected TotalFlex\Table.');
		}
	}
	
    /**
     * Check if this field should be included in specific context(s)
     *
     * @param int Context(s) to check.
     * @return boolean Field's configuration to be included or not
     */
    public function applyToContext($context) {
        return (($context & $this->getContexts()) !== 0);
    }

    /**
     * Gets the Column name
     *
     * @return string Column name
     */
    public function getColumn() {
        return $this->_column;
    }

    /**
     * Sets the Column name
     *
     * @param string Column name
     * @return self
     * @throws \InvalidArgumentException 
     */
    public function setColumn($column) {
    	if (empty($column)) {
    		throw new \InvalidArgumentException('Column name cannot be empty.');
    	}

        $this->_column = $column;
        return $this;
    }

    /**
     * Gets the Field label
     *
     * @return string Field label
     */
    public function getLabel() {
        return $this->_label;
    }

    /**
     * Sets the Field label
     *
     * @param string Field label
     * @return self
     */
    public function setLabel($label) {
        $this->_label = $label;
        return $this;
    }

    /**
     * Gets the HTML Input Type
     *
     * @return string HTML Input Type
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * Sets the HTML Input Type
     *
     * @param string HTML Input Type
     * @return self
     */
    public function setType($type) {
        $this->_type = $type;
        return $this;
    }

    /**
     * Check if this is a primary key
     *
     * @return boolean Is a primary key
     */
    public function isPrimaryKey() {
        return $this->_primaryKey;
    }

    /**
     * Sets if this is a primary key
     *
     * @param boolean Is a primary key
     * @return self
     */
    public function setPrimaryKey($primaryKey) {
        $this->_primaryKey = $primaryKey;
        return $this;
    }

    /**
     * Gets the contexts this fields is allowed
     *
     * @return int The contexts this fields is allowed
     */
    public function getContexts() {
        return $this->_contexts;
    }

    /**
     * Sets the contexts this fields is allowed
     *
     * @param int The contexts this fields is allowed. See TotalFlex::Ctx* constants.
     * @return self
     */
    public function setContexts($contexts) {
        $this->_contexts = $contexts;
        return $this;
    }

    /**
     * Gets the rules to this field
     *
     * @return array[IRule] Rules to this field
     */
    public function getRules() {
        return $this->_rules;
    }

    /**
     * Sets the rules to this field
     *
     * @param array[IRule] Rules to this field
     * @return self
     */
    public function setRules($rules) {
        $this->_rules = $rules;
        return $this;
    }

    /**
     * Adds a rule to the ruleset of this field
     *
     * @param IRule $rule The rule
     * @return self
     */
    public function addRule($rule) {
        $this->_rules[] = $rule;
        return $this;
    }

    /**
     * Validates a value with this fields rules
     *
     * @param mixed $value Value to validate
     * @return boolean Value validity
     */
    public function validate($value) {
        foreach ($this->_rules as $rule) {
            $valid = $rule->validate($value);
            if (!$valid) {
                return false;
            }
        }

        return true;
    }

    /**
     * Return it's table to more semantic usage of TotalFlex
     *
     * @return TotalFlex\Table This field's table
     * @throws \RuntimeException when it haven't a table
     */
    public function then() {
    	if ($this->_table === null) {
    		throw new \RuntimeException('This field is not associated to a table.');
    	}

    	return $this->_table;
    }
}