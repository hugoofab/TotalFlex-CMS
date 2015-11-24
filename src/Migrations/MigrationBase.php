<?php

namespace TotalFlex\Migrations;

class MigrationBase {
	/**
	 * @var \FluentPDO $_db Database to migrate
	 */
	private $_db;

	/**
	 * Initializes the migration, receiving database to execute it.
	 *
	 * @param \FluentPDO $db Database to execute
	 */
	public function __construct($db) {
		$this->_db = $db;
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
			$e->printStackTrace();
			return false;
		}
	}
}