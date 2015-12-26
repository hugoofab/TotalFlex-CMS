<?php

namespace TotalFlex;
use TotalFlex\Exception\AlreadyRegisteredTable;
use TotalFlex\Exception\InvalidField;
use TotalFlex\Exception\InvalidContext;
use TotalFlex\Exception\NotRegisteredTable;
use TotalFlex\Support\RequestParameters;
use \FluentPDO;

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
	 * @var string $_method The method of the forms
	 */
	private $_method;

	/**
	 * @var \PDO $_targetDb Target database, with business entities.
	 */
	private $_targetDb;

	/**
	 * @var array $_views Views configuration
	 */
	private $_views;

	
	/**
	 * Constructs the CRUDManager
	 *
	 * @param string $callbackUrl Callback URL to CRUD forms
	 * @param string $method The form method
	 * @param string $dsn Datasource name
	 * @param string $user (Optional) Username to the database
	 * @param string $pass (Optional) Password to the database
	 * @param array $opts (Optional) Associative array with driver-specific options
	 * @throws \RuntimeException
	 */
	public function __construct ( $pdo ) {
	// public function __construct(/** $callbackUrl, $method, $dsn, $user, $pass, $opts **/) {
		/*
		 * Initializes the DB Connection
		 */

		// $pdo should be instance of PDO
		// $this->_targetDb = new FluentPDO($pdo);
		$this->_targetDb = $pdo;

		// $argc = func_num_args();
		// $argv = func_get_args();

		// switch ($argc) {
		// 	case 3:
		// 	case 4:
		// 	case 5:
		// 	case 6:
		// 		$this->_callbackUrl = array_shift($argv);
		// 		$this->_method = array_shift($argv);
		// 		$reflection = new \ReflectionClass('\PDO');
		// 		$this->_targetDb = $reflection->newInstanceArgs($argv);
		// 		break;

		// 	default:
		// 		throw new \RuntimeException('Unexpected number of arguments (' . $argc . '). Expected 3..6.');
		// }

		/*
		 * Initializes the views
		 */
		$this->_views = [];

		/*
		 * Initializes the contexts
		 */
		$this->_contexts = [];

	}

	/**
	 * Process post requests from a view on a context
	 * @param  [type] $view [string] view name
	 * @param  [type] $contexts one context
	 * @return [type]          [description]
	 */
    public function processPost ( $viewName , $context ) {

    	if ( empty ( $_POST ) ) return ;
		if ( $this->hasContext ( TotalFlex::CtxCreate , $context ) ) return $this->_processCreate ( $viewName );
		if ( $this->hasContext ( TotalFlex::CtxDelete , $context ) ) return $this->_processDelete ( $viewName );
		if ( $this->hasContext ( TotalFlex::CtxUpdate , $context ) ) return $this->_processUpdate ( $viewName );

		// if ( $this->hasContext ( TotalFlex::CtxRead , $context ) ) $this->_processDelete ( $view );

		// TFFields[business_entity][1][fields][name]

    }

    private function _processDelete ( $viewName ) {
		$view = $this->getView ( $viewName );

    }

    private function _processUpdate ( $viewName ) {
		$view = $this->getView ( $viewName );

    }

    private function _processCreate ( $viewName ) {

		$myContext = TotalFlex::CtxCreate ;
		$view      = $this->getView ( $viewName );
		if ( empty ( $_POST['TFFields'][$viewName][$myContext] ) ) return ;
		
		$fieldList = $view->getFieldsFromContext ( $myContext );

		$queryFieldList = array ( );
		foreach ( $fieldList as $Field ) {
			if ( !is_a ( $Field , 'TotalFlex\Field\Field' ) ) continue ;
			if ( $Field->isPrimaryKey() ) continue ;
			$queryFieldList[$Field->getColumn()] = $Field->getValue();
		}

		$query = $view->queryBuilder()->getCreateQuery($queryFieldList);
		$exec  = $this->_targetDb->query($query);

		if ( $exec ) {
			foreach ( $fieldList as $Field ) {
				if ( !is_a ( $Field , 'TotalFlex\Field\Field' ) ) continue ;
				if ( $Field->isPrimaryKey() ) continue ;
				$Field->setValue('');
			}
			// need redirect here to avoid user repost
		} else {
			$errorInfo = $this->_targetDb->errorInfo();
			// pre($this->_targetDb->errorCode());
			Feedback::addMessage ( $errorInfo[2] , Feedback::MESSAGE_DANGER );
		}

    }



    /**
     * Check if there is a given context group have a specific context
     *
     * @param int Context(s) to check.
     * @return boolean Field's configuration to be included or not
     */
    public function hasContext( $needle , $haystack ) {
        return (($needle & $haystack) !== 0);
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
	 * Register View configuration.
	 *
	 * @param string $name The name of the View to configure
	 * @param array[TotalFlex\Field] $fields The View fields
	 * @throws TotalFlex\Exception\AlreadyRegisteredView
	 * @throws TotalFlex\Exception\InvalidField
	 */
	public function registerView($name, $alias = null) {

		if (is_null($alias)) {
			$alias = $name;
		}

		if (isset($this->_views[$alias])) {
			throw new AlreadyRegisteredView($alias);
		}

		// Register the View and return it
		$this->_views[$alias] = new View($name);
		return $this->_views[$alias];
		
	}

	/**
	 * Generate form data from table in context
	 *
	 * @param string $tableName The table
	 * @param int $context The context to generate
	 * @param IQueryFormatter $formatter (Optional) Formatter to output data
	 * @param boolean $includeContext (Optional) Whether or not to include context and entity hidden fields
	 * @return mixed Generated fields
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	// public function generate($tableName, $context, $Formatter = null , $includeContext = true) {
	// 	switch ($context) {
	// 		case self::CtxNone:
	// 			throw new InvalidContext("CtxNone");
	// 			break;

	// 		case self::CtxCreate:
	// 			return $this->generateCreateContext($tableName, $Formatter, $includeContext);
	// 			break;

	// 		case self::CtxRead:
	// 			return $this->generateReadContext($tableName, $Formatter, $includeContext);
	// 			break;

	// 		case self::CtxUpdate:
	// 			return $this->generateUpdateContext($tableName, $Formatter, $includeContext);
	// 			break;

	// 		case self::CtxDelete:
	// 			return $this->generateDeleteContext($tableName, $Formatter, $includeContext);
	// 			break;

	// 		default: 
	// 			throw new InvalidContext($context);
	// 	}
	// }

	/**
	 * Handles form callback 
	 *
	 * @return boolean Operation status (success, failed)
	 * @throws \RuntimeException
	 */
	// public function handleCallback() {

	// 	$requestParameters = new RequestParameters();
		
	// 	if (!$requestParameters->received('totalflex')) {
	// 		throw new \RuntimeException("Expected totalflex keys to be in the request.");
	// 	}

	// 	/**
	// 	 * @todo: Necessary sanitize?
	// 	 */
	// 	$totalFlexData = $requestParameters->get('totalflex');
	// 	$entity = $totalFlexData['entity'];
	// 	$context = $totalFlexData['ctx'];

	// 	switch ($context) {
	// 		case self::CtxNone:
	// 			throw new InvalidContext("CtxNone");
	// 			break;

	// 		case self::CtxCreate:
	// 			return $this->handleCreateCallback($entity);
	// 			break;

	// 		case self::CtxRead:
	// 			return $this->handleReadCallback($entity);
	// 			break;

	// 		case self::CtxUpdate:
	// 			return $this->handleUpdateCallback($entity);
	// 			break;

	// 		case self::CtxDelete:
	// 			return $this->handleDeleteCallback($entity);
	// 			break;

	// 		default: 
	// 			throw new InvalidContext($context);	
	// 	}
	// }

	/**
	 * Generate form data to Create context
	 *
	 * @param string $tableName The table name
	 * @param IQueryFormatter $formatter (Optional) Formatter to output data
	 * @param boolean $includeContext (Optional) Whether or not to include context and entity hidden fields
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	// public function generateCreateContext($tableName, $Formatter = null , $includeContext = true) {

	// 	$Table = $this->getTable($tableName);
		
	// 	if ( $Formatter === null ) {
	// 		// 'TotalFlex\QueryFormatter\Dummy'
	// 		$Formatter = new TotalFlex\QueryFormatter\Dummy ( );
	// 	}

	// 	if ($includeContext) {
	// 		$Formatter->addField('totalflex[entity]', '', 'hidden', $tableName);
	// 		$Formatter->addField('totalflex[ctx]', '', 'hidden', self::CtxCreate);
	// 	}

	// 	$Formatter->setTable ( $Table );

	// 	/**
	// 	 * @todo I18N
	// 	 */
	// 	// $Formatter->addField('', '', 'submit', 'Submit');
	// 	// $Formatter->addButton('', '', 'submit', 'Submit');

	// 	return $Formatter->generate();
	// }

	/**
	 * Handles callback in the Create context
	 *
	 * @param string $tableName The table name 
	 * @return boolean Operation status (success, failed)
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	// public function handleCreateCallback($tableName) {
	// 	$table = $this->getTable($tableName);
	// 	$requestParameters = new RequestParameters();

	// 	$creationValues = [];
	// 	foreach ($table->getFields() as $field) {
	// 		if (!$field->applyToContext(self::CtxCreate)) {
	// 			continue;
	// 		}

	// 		$columnName = $field->getColumn();
			
	// 		if (!$requestParameters->received($columnName)) {
	// 			/**
	// 			 * @todo: Add messaging system
	// 			 */

	// 			print_r("Expected parameter `$columnName` not found");
	// 			return false;
	// 		}

	// 		$value = $requestParameters->get($columnName);

	// 		if ($field->validate($value)) {
	// 			$creationValues[$columnName] = $value;
	// 		} else {
	// 			print_r("Invalid value to field `$columnName`");
	// 			return false;
	// 		}
	// 	}
		
	// 	try {
	// 		$this->_targetDb->beginTransaction();
	// 		$table->executePreCreationCallback($creationValues);
			
	// 		$fluentDb = new FluentPDO($this->_targetDb);
	// 		$fluentDb
	// 			->insertInto($table->getName())
	// 			->values($creationValues)
	// 			->execute();
			
	// 		$table->executePostCreationCallback($creationValues);
	// 		$this->_targetDb->commit();
	// 	} catch (\Exception $e) {
	// 		$this->_targetDb->rollBack();
	// 		return false;
	// 	}
		

	// 	return true;
	// }

	/**
	 * Generate form data to Read context
	 *
	 * @param string $tableName The table name
	 * @param IQueryFormatter $formatter (Optional) Formatter to output data
	 * @param boolean $includeContext (Optional) Whether or not to include context and entity hidden fields
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	// public function generateReadContext($tableName, $formatter = 'TotalFlex\QueryFormatter\Dummy', $includeContext = true) {
	// 	$table = $this->getTable($tableName);

	// 	/** 
	// 	 * @todo 
	// 	 */
	// }

	/**
	 * Handles callback in the Read context
	 *
	 * @param string $tableName The table name 
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	// public function handleReadCallback($tableName) {
	// 	/**
	// 	 * @todo
	// 	 */
	// }

	/**
	 * Generate form data to Update context
	 *
	 * @param string $tableName The table name
	 * @param IQueryFormatter $formatter (Optional) Formatter to output data
	 * @param boolean $includeContext (Optional) Whether or not to include context and entity hidden fields
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	// public function generateUpdateContext($tableName, $formatter = 'TotalFlex\QueryFormatter\Dummy', $includeContext = true) {
	// 	$table = $this->getTable($tableName);

	// 	/** 
	// 	 * @todo 
	// 	 */
	// }

	/**
	 * Handles callback in the Update context
	 *
	 * @param string $tableName The table name 
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	// public function handleUpdateCallback($tableName) {
	// 	/**
	// 	 * @todo
	// 	 */
	// }

	/**
	 * Generate form data to Delete context
	 *
	 * @param string $tableName The table name
	 * @param IQueryFormatter $formatter (Optional) Formatter to output data
	 * @param boolean $includeContext (Optional) Whether or not to include context and entity hidden fields
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	// public function generateDeleteContext($tableName, $formatter = 'TotalFlex\QueryFormatter\Dummy', $includeContext = true) {
	// 	$table = $this->getTable($tableName);

	// 	/** 
	// 	 * @todo 
	// 	 */
	// }

	/**
	 * Handles callback in the Delete context
	 *
	 * @param string $tableName The table name 
	 * @throws TotalFlex\Exception\NotRegisteredTable
	 */
	// public function handleDeleteCallback($tableName) {
	// 	/**
	// 	 * @todo
	// 	 */
	// }

	/**
	 * Gets registered view
	 *
	 * @param string $viewName The view name
	 * @return TotalFlex\view The view identified by $viewName
	 * @throws TotalFlex\Exception\AlreadyRegisteredView
	 */
	public function getView($viewName) {
		if (!isset($this->_views[$viewName])) {
			throw new NotRegisteredView($viewName);
		}
		
		return $this->_views[$viewName];
	}



}