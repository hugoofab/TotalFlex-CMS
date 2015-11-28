<?php

namespace TotalFlex;
use TotalFlex\Exception\AlreadyRegisteredTable;
use TotalFlex\Exception\InvalidField;
use TotalFlex\Exception\InvalidContext;
use TotalFlex\Exception\NotRegisteredTable;

class TotalFlex {
	/**
	 * @var CtxNone No context constant
	 */
	const CtxNone	= 0b0000;
	/**
	 * @var CtxCreate Create context constant
	 */
	const CtxCreate	= 0b0001;
	/**
	 * @var CtxRead Read context constant
	 */
	const CtxRead	= 0b0010;
	/**
	 * @var CtxUpdate Update context constant
	 */
	const CtxUpdate	= 0b0100;
	/**
	 * @var CtxDelete Delete context constant
	 */
	const CtxDelete	= 0b1000;

	/**
	 * @var string $_callbackUrl Callback URL to forms access TotalFlex
	 */
	private $_callbackUrl;

	/**
	 * @var \PDO $_targetDb Target database, with business entities.
	 */
	private $_targetDb;

	/**
	 * @var array $_tables Tables configuration
	 */
	private $_tables;

	
	/**
	 * Constructs the CRUDManager
	 *
	 * @param string $callbackUrl Callback URL to CRUD forms
	 * @param string $dsn Datasource name
	 * @param string $user (Optional) Username to the database
	 * @param string $pass (Optional) Password to the database
	 * @param array $opts (Optional) Associative array with driver-specific options
	 * @throws \RuntimeException
	 */
	public function __construct(/** $callbackUrl, $dsn, $user, $pass, $opts **/) {
		/*
		 * Initializes the DB Connection
		 */

		$argc = func_num_args();
		$argv = func_get_args();

		switch ($argc) {
			case 2:
			case 3:
			case 4:
			case 5:
				$this->_callbackUrl = array_shift($argv);
				$reflection = new \ReflectionClass('\PDO');
				$this->_targetDb = $reflection->newInstanceArgs($argv);
				break;

			default:
				throw new \RuntimeException('Unexpected number of arguments (' . $argc . '). Expected 2..5.');
		}

		/*
		 * Initializes the tables
		 */
		$this->_tables = [];

		/*
		 * Initializes the contexts
		 */
		$this->_contexts = [];
	}

	/**
	 * Gets the callback URL
	 *
	 * @return string The callback URL
	 */
	public function getCallbackUrl() {
		return $this->_callbackUrl;
	}

	/**
	 * Sets the callback URL
	 *
	 * @param string $callbackUrl The callback URL
	 * @return self
	 */
	public function setCallbackUrl($callbackUrl) {
		$this->_callbackUrl = $callbackUrl;
		return $this;
	}

	/**
	 * Register table configuration.
	 *
	 * @param string $name The name of the table to configure
	 * @param array[TotalFlex\Field] $fields The table fields
	 * @throws TotalFlex\Exception\AlreadyRegisteredTable
	 * @throws TotalFlex\Exception\InvalidField
	 */
	public function registerTable($name) {
		if (isset($this->_tables[$name])) {
			throw new AlreadyRegisteredTable($name);
		}

		// Register the table and return it
		$this->_tables[$name] = new Table($name);
		return $this->_tables[$name];
	}

	/**
	 * Generate form data from table in context
	 *
	 * @param string $tableName The table
	 * @param int $context The context to generate
	 * @param IQueryFormatter $formatter (Optional) Formatter to output data
	 * @return mixed Generated fields
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	public function generate($tableName, $context, $formatter = 'TotalFlex\QueryFormatter\Dummy') {
		switch ($context) {
			case self::CtxNone:
				throw new InvalidContext("CtxNone");
				break;

			case self::CtxCreate:
				return $this->generateCreateContext($tableName, $formatter);
				break;

			case self::CtxRead:
				return $this->generateReadContext($tableName, $formatter);
				break;

			case self::CtxUpdate:
				return $this->generateUpdateContext($tableName, $formatter);
				break;

			case self::CtxDelete:
				return $this->generateDeleteContext($tableName, $formatter);
				break;

			default: 
				throw new InvalidContext($context);
		}
	}

	/**
	 * Handles form callback 
	 *
	 * @return boolean Operation status (success, failed)
	 * @throws \RuntimeException
	 */
	public function handleCallback() {
		if (!isset($_POST['totalflex'])) {
			throw new \RuntimeException("Expected totalflex keys to be in the request.");
		}

		// @todo: Necessary sanitize?
		$entity = $_POST['totalflex']['entity'];
		$context = $_POST['totalflex']['ctx'];

		switch ($context) {
			case self::CtxNone:
				throw new InvalidContext("CtxNone");
				break;

			case self::CtxCreate:
				return $this->handleCreateCallback($entity);
				break;

			case self::CtxRead:
				return $this->handleReadCallback($entity);
				break;

			case self::CtxUpdate:
				return $this->handleUpdateCallback($entity);
				break;

			case self::CtxDelete:
				return $this->handleDeleteCallback($entity);
				break;

			default: 
				throw new InvalidContext($context);	
		}
	}

	/**
	 * Generate form data to Create context
	 *
	 * @param string $tableName The table name
	 * @param IQueryFormatter $formatter (Optional) Formatter to output data
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	public function generateCreateContext($tableName, $formatter = 'TotalFlex\QueryFormatter\Dummy') {
		$table = $this->_getTable($tableName);
		
		$formatterInst = new $formatter($this->_callbackUrl);
		$formatterInst->addField('totalflex[entity]', '', 'hidden', $tableName);
		$formatterInst->addField('totalflex[ctx]', '', 'hidden', self::CtxCreate);

		foreach ($table->getFields() as $field) {
			if (!$field->applyToContext(self::CtxCreate)) {
				continue;
			}

			$formatterInst->addField(
				$field->getColumn(), 
				$field->getLabel(),
				$field->getType()
			);
		}

		/**
		 * @todo I18N
		 */
		$formatterInst->addField('', '', 'submit', 'Submit');

		return $formatterInst->generate();
	}

	/**
	 * Handles callback in the Create context
	 *
	 * @param string $tableName The table name 
	 * @return boolean Operation status (success, failed)
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	public function handleCreateCallback($tableName) {
		$table = $this->_getTable($tableName);
		
		$creationValues = [];
		foreach ($table->getFields() as $field) {
			if (!$field->applyToContext(self::CtxCreate)) {
				continue;
			}

			$columnName = $field->getColumn();

			if (!isset($_POST[$columnName])) {
				/**
				 * @todo: Add messaging system
				 */

				print_r("Expected parameter `$columnName` not found");
				return false;
			}

			/**
			 * @todo Sanitize? (Will be passed by binding to PDO)
			 */
			$value = $_POST[$columnName];

			if ($field->validate($value)) {
				$creationValues[$columnName] = $value;
			} else {
				print_r("INVALID VALUE");
				return false;
			}
		}
		
		try {
			/**
			 * @todo Initialize transaction
			 * @todo Execute pre-creation script
			 * @todo Insert $creationValues into $tableName
			 * @todo Execute post-creation script
			 */	
		} catch (\Exception $e) {
			/**
			 * @todo Rollback
			 */	

			return false;
		}
		

		return true;
	}

	/**
	 * Generate form data to Read context
	 *
	 * @param string $tableName The table name
	 * @param IQueryFormatter $formatter (Optional) Formatter to output data
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	public function generateReadContext($tableName, $formatter = 'TotalFlex\QueryFormatter\Dummy') {
		$table = $this->_getTable($tableName);

		/** 
		 * @todo 
		 */
	}

	/**
	 * Handles callback in the Read context
	 *
	 * @param string $tableName The table name 
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	public function handleReadCallback($tableName) {
		/**
		 * @todo
		 */
	}

	/**
	 * Generate form data to Update context
	 *
	 * @param string $tableName The table name
	 * @param IQueryFormatter $formatter (Optional) Formatter to output data
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	public function generateUpdateContext($tableName, $formatter = 'TotalFlex\QueryFormatter\Dummy') {
		$table = $this->_getTable($tableName);

		/** 
		 * @todo 
		 */
	}

	/**
	 * Handles callback in the Update context
	 *
	 * @param string $tableName The table name 
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	public function handleUpdateCallback($tableName) {
		/**
		 * @todo
		 */
	}

	/**
	 * Generate form data to Delete context
	 *
	 * @param string $tableName The table name
	 * @param IQueryFormatter $formatter (Optional) Formatter to output data
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	public function generateDeleteContext($tableName, $formatter = 'TotalFlex\QueryFormatter\Dummy') {
		$table = $this->_getTable($tableName);

		/** 
		 * @todo 
		 */
	}

	/**
	 * Handles callback in the Delete context
	 *
	 * @param string $tableName The table name 
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	public function handleDeleteCallback($tableName) {
		/**
		 * @todo
		 */
	}

	/**
	 * Gets registered table
	 *
	 * @param string $tableName The table name
	 * @return TotalFlex\Table The table identified by $tableName
	 * @throws TotalFlex\Exception\AlreadyRegisteredTable
	 */
	protected function _getTable($tableName) {
		if (!isset($this->_tables[$tableName])) {
			throw new AlreadyRegisteredTable($tableName);
		}
		
		return $this->_tables[$tableName];
	}
}