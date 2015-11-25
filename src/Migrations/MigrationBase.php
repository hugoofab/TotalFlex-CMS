<?php

namespace TotalFlex\Migrations;

class MigrationBase {
	/**
	 * @var \FluentPDO $_db Database to migrate
	 */
	private $_db;

	/**
	 * @var string $_tablePreffix Tables' preffix
	 */
	private $_tablePreffix;

	/**
	 * Initializes the migration, receiving database to execute it.
	 *
	 * @param \FluentPDO $db Database to execute
	 * @param string $tablePreffix Tables' preffix
	 */
	public function __construct($db, $tablePreffix) {
		$this->_db = $db;
		$this->_tablePreffix = $tablePreffix;
	}

	/**
	 * Get table name for generic Total Flex table
	 *
	 * @param string $tableName Table name
	 * @return string Real table name
	 */
	public function getTablename($tableName) {
		return $this->_tablePreffix . $tableName;
	}

	/**
	 * Runs the migration inside a transaction
	 */
	public function run() {
		$this->_db->getPdo()->beginTransaction();
		try {
			$this->execute($this->_db);
			$this->_db->getPdo()->commit();
			return true;
		} catch (\Exception $e) {
			$this->_db->getPdo()->rollback();
			return false;
		}
	}

	/**
	 * Effectively executes the migration
	 *
	 * @param \FluentPDO $db The database to execute
	 * @throws \RuntimeException 
	 */
	public function execute($db) {
		throw new \RuntimeException("This function must be implemented.");
	}
}