<?php

namespace TotalFlex;
use \FluentPDO;
use TotalFlex\Migrations\MigrationBase;

class TotalFlex {
	/**
	 * @var \FluentPDO $_configDb TotalFlex configuration database
	 */
	private $_configDb;

	/**
	 * @var \FluentPDO $_targetDb Target database, with business entities.
	 */
	private $_targetDb;

	/**
	 * @var string $_configDbPreffix Preffix of Total Flex's tables in the SQLite database
	 */
	private $_configDbPreffix;

	/**
	 * Constructs the CRUDManager. Will pass all parameters to PDO constructor.
	 *
	 * @param string $databaseFilename Name of the SQLite file in which TotalFlex will persist its configuration.
	 * @param string $_configDbPreffix Preffix of Total Flex's tables in the SQLite database
	 * @param array $targetDatabaseOptions Connection details to the database with the CRUD needs.
	 *		Can contain the following keys:
	 * 		- dsn: Datasource name
	 * 		- user: (Optional) Username to the database
	 * 		- pass: (Optional) Password to the database
	 * 		- opts: (Optional) Associative array with driver-specific options
	 */
	public function __construct($databaseFilename, $configDbPreffix, $targetDatabaseOptions) {
		$this->_configDbPreffix = $configDbPreffix;

		$this->_configDb = new \FluentPDO(
			new \PDO('sqlite:' . $databaseFilename)
		);

		$targetOptions = ['dsn', 'user', 'pass', 'opts'];
		foreach ($targetOptions as $targetOption) {
			if (!isset($targetDatabaseOptions[$targetOption])) {
				$targetDatabaseOptions[$targetOption] = null;
			}
		}

		$this->_targetDb = new \FluentPDO(
			new \PDO(
				$targetDatabaseOptions['dsn'],
				$targetDatabaseOptions['user'],
				$targetDatabaseOptions['pass'],
				$targetDatabaseOptions['opts']
			)
		);

		
		$this->_verifyDatabaseSchema();
	}

	/**
	 * Get the database version
	 *
	 * @return int Database version
	 */
	public function getDbVersion() {
		$configTable = $this->_configDbPreffix . 'config';
		$versionStmt = $this->_configDb->from($configTable)->select('value')->where('key', 'version');
		$version = $versionStmt->fetch();

		if ($version == null) {
			$version = 0;
		}

		return $version;
	}

	/**
	 * Verifies the TotalFlex configuration database schema
	 */
	private function _verifyDatabaseSchema() {
		// Base configuration table
		$configTable = $this->_configDbPreffix . 'config';
		$this->_configDb->getPdo()->query("
			CREATE TABLE IF NOT EXISTS $configTable (
				key TEXT, value TEXT
			)"
		);

		// Total Flex version
		$version = $this->getDbVersion();
		
		// Verify and execute necessary migrations
		$migrations = $this->_getMigrationsFromVersion($version);
		foreach ($migrations as $migrationClass) {
			// Execute migration 
			$migration = new $migrationClass($this->_configDb, $this->_configDbPreffix);
			$result = $migration->run();

			// Check result
			if (!$result) {
				throw new \RuntimeException("Migrations not complete. Cannot continue.");
				break;
			}

			$reflection = new \ReflectionClass($migrationClass);
			$migrationVersion = (int)$reflection->getConstant('VERSION');
			$this->_updateDatabaseVersionTo($migrationVersion);
		}
	}

	/**
	 * Updates the database version
	 *
	 * @param int $newVersion New database version
	 */
	protected function _updateDatabaseVersionTo($newVersion) {
		$configTable = $this->_configDbPreffix . 'config';
		$this->_configDb->getPdo()->query("
			INSERT OR REPLACE INTO $configTable
			(key, value) VALUES ('version', $newVersion);
		");
	}

	/**
	 * Get migration classes to execute from provided db version
	 *
	 * @todo Refactor, maybe?
	 *
	 * @param int $version Active database version
	 * @return array Migration classes to execute
	 */
	private function _getMigrationsFromVersion($version) {
		// All migrations
		$migrations = glob(__DIR__ . '/Migrations/Migration[0-9]*.php');
		$responseMigrations = [];

		foreach ($migrations as $migration) {
			// Get MigrationXYZ.php from 
			$_classfile = explode('/', $migration);
			$classfile = $_classfile[count($_classfile) - 1];

			// Removes .php
			$class = substr($classfile, 0, strlen($classfile) - 4);

			// Class w/ Namespace
			$classWithNamespace = 'TotalFlex\\Migrations\\' . $class;

			// Get migration version
			$reflection = new \ReflectionClass($classWithNamespace);
			$migrationVersion = (int)$reflection->getConstant('VERSION');

			if ($migrationVersion > $version) {
				$responseMigrations[] = $classWithNamespace;
			}
		}

		return $responseMigrations;
	}
}