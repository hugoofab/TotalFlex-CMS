<?php

namespace TotalFlex;

use TotalFlex\Exception\InvalidField;
use TotalFlex\Exception\AlreadyRegisteredField;

class Table {
	/**
	 * @var string Table name
	 */
	private $_name;

	/**
	 * @var array Table fields
	 */
	private $_fields;

    /**
     * @var callable Callback to execute before inserts on this table
     */
    private $_preCreationCallback;

    /**
     * @var callable Callback to execute after inserts on this table
     */
    private $_postCreationCallback;

    /**
     * Constructs the table
     *
     * @param string $name The table name
     */
    public function __construct($name) {
        $this->setName($name);
    }

    /**
     * Gets the table name
     *
     * @return string Table name
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Sets the table name
     *
     * @param string Table name
     * @return self
     */
    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    /**
     * Gets the table fields
     * @todo Defensive copy fields before returning it
     * @return array Table fields
     */
    public function getFields() {
        return $this->_fields;
    }


    /**
     * Gets the callback to execute before inserts on this table
     * @return callable|null The callback to execute before inserts on this table
     */
    public function getPreCreationCallback() {
        return $this->_preCreationCallback;
    }

    /**
     * Sets the callback to execute before inserts on this table
     *
     * @param callable|null $callback The callback to execute before inserts on this table
     * @return self
     */
    public function setPreCreationCallback($callback) {
        $this->_preCreationCallback = $callback;
        return $this;
    }

    /**
     * Executes the callback before inserts on this table
     *
     * @param array $creationValues The values which will be inserted
     * @return mixed The callback return or null, if it isn't defined
     */
    public function executePreCreationCallback($creationValues) {
        if ($this->_preCreationCallback) {
            return call_user_func($this->_preCreationCallback, $creationValues);
        }

        return null;
    }

    /**
     * Gets the callback to execute after inserts on this table
     * @return callable|null The callback to execute after inserts on this table
     */
    public function getPostCreationCallback() {
        return $this->_postCreationCallback;
    }

    /**
     * Sets the callback to execute after inserts on this table
     *
     * @param callable|null  $callbackThe callback to execute after inserts on this table
     * @return self
     */
    public function setPostCreationCallback($callback) {
        $this->_postCreationCallback = $callback;
        return $this;
    }

    /**
     * Executes the callback after inserts on this table
     *
     * @param array $creationValues The values which were inserted
     * @return mixed The callback return or null, if it isn't defined
     */
    public function executePostCreationCallback($creationValues) {
        if ($this->_postCreationCallback) {
            return call_user_func($this->_postCreationCallback, $creationValues);
        }

        return null;
    }

    /**
     * Add a field to the table
     *
     * @param string $columnName The field column name
     * @param string $label The field label
     * @return TotalFlex\Field The created field
     * @throws TotalFlex\Exception\InvalidField
     * @throws TotalFlex\Exception\AlreadyRegisteredField
     */
    public function addField($columnName , $label = "" ) {

        if (isset($this->_fields[$columnName])) {
			throw new AlreadyRegisteredField(
				'Field `' . $columnName . '` was already registered in the table `' . $this->getName() . '`.'
			);
		}

		$this->_fields[$columnName] = new Field($columnName, $this);
		if ( !empty ( $label ) ) $this->_fields[$columnName]->setLabel($label);
		return $this->_fields[$columnName];
    }
}