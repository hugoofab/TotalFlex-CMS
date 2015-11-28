<?php

namespace TotalFlex;

use TotalFlex\Exception\InvalidField;
use TotalFlex\Exception\AlreadyRegisteredField;

class Table {
	/**
	 * @var string Table name
	 */
	private $_tableName;

	/**
	 * @var array Table fields
	 */
	private $_fields;

    /**
     * Gets the table name
     *
     * @return string Table name
     */
    public function getTableName() {
        return $this->_tableName;
    }

    /**
     * Sets the table name
     *
     * @param string Table name
     * @return self
     */
    public function setTableName($tableName) {
        $this->_tableName = $tableName;
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
     * Add a field to the table
     *
     * @param string $columnName The field column name
     * @return TotalFlex\Field The created field
     * @throws TotalFlex\Exception\InvalidField
     * @throws TotalFlex\Exception\AlreadyRegisteredField
     */
    public function addField($columnName) {
        if (isset($this->_fields[$columnName])) {
			throw new AlreadyRegisteredField(
				'Field `' . $columnName . '` was already registered in the table `' . $this->getName() . '`.'
			);
		}

		$this->_fields[$columnName] = new Field($columnName, $this);
		return $this->_fields[$columnName];
    }
}