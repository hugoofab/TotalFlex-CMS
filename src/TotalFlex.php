<?php

namespace TotalFlex;
use \FluentPDO;
use TotalFlex\Migrations\MigrationBase;

class TotalFlex {
	/**
	 * @var \FluentPDO $configDb TotalFlex configuration database
	 */
	private $configDb;

	/**
	 * @var \FluentPDO $targetDb Target database, with business entities.
	 */
	private $targetDb;

	/**
	 * Constructs the CRUDManager. Will pass all parameters to PDO constructor.
	 *
	 * @param string $databaseFilename Name of the SQLite file in which TotalFlex will persist its configuration.
	 * @param array $targetDatabaseOptions Connection details to the database with the CRUD needs.
	 *		Can contain the following keys:
	 * 		- dsn: Datasource name
	 * 		- user: (Optional) Username to the database
	 * 		- pass: (Optional) Password to the database
	 * 		- opts: (Optional) Associative array with driver-specific options
	 */
	public function __construct($databaseFilename, $targetDatabaseOptions) {
		$this->configDb = new \FluentPDO(
			new \PDO('sqlite:' . $databaseFilename)
		);

		$targetOptions = ['dsn', 'user', 'pass', 'opts'];
		foreach ($targetOptions as $targetOption) {
			if (!isset($targetDatabaseOptions[$targetOption])) {
				$targetDatabaseOptions[$targetOption] = null;
			}
		}

		$this->targetDb = new \FluentPDO(
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
		$versionStmt = $this->configDb->from('config')->select('value')->where('key', 'version');
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
		$this->configDb->getPdo()->query("CREATE TABLE IF NOT EXISTS config(key TEXT, value TEXT)");
		
		// Total Flex version
		$version = $this->getDbVersion();

		// Verify migrations
		$this->_getMigrationsFromVersion($version);
	}

	/**
	 * Get migration classes to execute from provided db version
	 *
	 * @param int $version Active database version
	 * @return array Migration classes to execute
	 */
	private function _getMigrationsFromVersion($version) {
		$migrations = glob(__DIR__ . '/Migrations/Migration[0-9]*.php');
		
		foreach ($migrations as $migration) {
			include_once($migration);
			$_classfile = explode('/', $migration);
			$classfile = $_classfile[count($_classfile) - 1];
			$class = substr($classfile, 0, strlen($classfile) - 4);
			$classWithNamespace = 'TotalFlex\\Migrations\\' . $class;

			print_r($classWithNamespace);
		}
	}
}