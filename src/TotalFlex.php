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
	 * @var used to hasing
	 */
	const SECURITY_SALT = "wYWZzcjM5TMidzN0QWOiljZhRDMwUTNkhDNwUDN0ETMxEDMwMDOygTZihTO5IDN2QGM2QWZyYTZmZ";

	/**
	 * @var string default formatter to simplify method calls
	 */
	private static $defaultFormatter = 'html';

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
	 * @var should be a anonymous function to be executed after success
	 */
	private $_successCallback = null ;

	/**
	 * @var should be a anonymous function to be executed after an error
	 */
	private $_errorCallback = null ;

	/**
	 * @var null default db to be used on all classes that need it. we expects it be a pdo instance
	 */
	protected static $defaultDB = null ;

    private $_feedbackMessageList = array (
    	'success' => array (
			TotalFlex::CtxCreate => "Saved sucessfully" ,
			TotalFlex::CtxRead   => "" ,
			TotalFlex::CtxUpdate => "Updated sucessfully" ,
			TotalFlex::CtxDelete => "Deleted sucessfully" ,
    	),
    	'error' => array (
			TotalFlex::CtxCreate => "Could not create" ,
			TotalFlex::CtxRead   => "" ,
			TotalFlex::CtxUpdate => "Could not update" ,
			TotalFlex::CtxDelete => "Could not delete" ,
    	)
    );


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
	public function __construct ( $pdo = null ) {
	// public function __construct(/** $callbackUrl, $method, $dsn, $user, $pass, $opts **/) {
		/*
		 * Initializes the DB Connection
		 */

		// $pdo should be instance of PDO
		// $this->_targetDb = new FluentPDO($pdo);

		if ( $pdo !== null ) {
			$this->_targetDb = $pdo;
		} else if ( self::$defaultDB !== null ) {
			$this->_targetDb = self::$defaultDB;
		} else {
			throw new DefaultDBNotSet ( "You have to set default db with TotalFlex::setDefaultDB() or give it as an argument in constructor method" );
		}

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
	 * Just do it! simplify form generation proccess by using default configuration and grouping method calls
	 * @param  [type] $viewName  [description]
	 * @param  [type] $context   [description]
	 * @param  [type] $callback  [description]
	 * @param  [type] $formatter [description]
	 * @return [type]            [description]
	 */
	public function doIt ( $context , $viewName = null , $callback = null , $formatter = null ) {
		
		if ( $viewName === null ) {
			if ( count ( $this->_views ) === 0 ) throw new Exception ( "No view set to show" );
			if ( count ( $this->_views ) > 1 ) {
				throw new \Exception ( "Missing view name" );
			} else {
				reset($this->_views);
				$view = current($this->_views);
				$viewName = $view->getName ();
			}
		}

		$this->processPost ( $viewName , $context , $callback ) ;
		return $this->generate ( $viewName , $context , $formatter );
	}

	public static function setDefaultDB ( $db ) {
		self::$defaultDB = $db ;
	}

	public static function getDefaultDB ( ) {
		return self::$defaultDB ;
	}

	/**
	 * Process post requests from a view on a context
	 * @param  [type] $view [string] view name
	 * @param  [type] $contexts one context
	 * @return [type]          [description]
	 */
    public function processPost ( $viewName , $context , $callback = null ) {

    	if ( empty ( $_POST ) ) return ;
		if ( $this->hasContext ( TotalFlex::CtxCreate , $context ) ) $return = $this->_processCreate ( $viewName );
		if ( $this->hasContext ( TotalFlex::CtxDelete , $context ) ) $return = $this->_processDelete ( $viewName );
		if ( $this->hasContext ( TotalFlex::CtxUpdate , $context ) ) $return = $this->_processUpdate ( $viewName );

		if ( gettype ( $callback ) === "object" ) {
			$callback($return);
		}

		return $return ;

    }

    private function _processDelete ( $viewName ) {
		$view = $this->getView ( $viewName );

    }

    private function _processUpdate ( $viewName ) {
// pr($_POST);

		$myContext = TotalFlex::CtxUpdate ;
		$view      = $this->getView ( $viewName );
		
		$this->setData($view);
		
		$fields = $view->getFields();

		foreach ( $fields as $field ) {
			if ( is_a ( $field , 'TotalFlex\Field\File' ) ) continue ;
			if ( is_a ( $field , 'TotalFlex\Button' ) ) continue ;
			if ( $field->skipOnUpdate() ) continue; 
			$field->setValue ( $_POST['TFFields'][$viewName][$myContext]['fields'][$field->getColumn()] );
		}

		// @todo precisa desacoplar. Chamar o $_POST dessa maneira tão engessada não é a melhor forma.
		if ( empty ( $_POST['TFFields'][$viewName][$myContext] ) ) return ;

		// ======================================================================================
			// THIS VALIDATION IS HERE BUT IT SHOULD NOT BE HERE
			// actually who create this hashcode is the formatter, so if TotalFlex recreate it, we are linking hardly TotalFlex with one formatter
			// the recomended is unbound this pieces of the system.
			// @todo #26
			$primaryKeyFields = $view->getPrimaryKeyFields ( );

			foreach ( $primaryKeyFields as $pkField ) {
				$hashSource = $pkField->getColumn()."=".$_POST['TFFields'][$view->getName()][$myContext]['fields'][$pkField->getColumn()];
			}
			$hash = sha1 ( md5 ( $hashSource ) . \TotalFlex\TotalFlex::SECURITY_SALT );
			if ( $hash !== $_POST['TFFields'][$view->getName()][$myContext]['validation_hash'] ) throw new \Exception ( "Invalid form data" );
		// ======================================================================================

		$fieldList = $view->getFieldsFromContext ( $myContext );

		$queryFieldList = $fieldValuesList = array ( );
		$errorList = array ( );
		foreach ( $fieldList as $Field ) {

			try {

				if ( !is_a ( $Field , 'TotalFlex\Field\Field' ) ) continue ;
				if ( $Field->isPrimaryKey ( ) ) continue ;
		
					// prs($Field->getColumn() . " " . $Field->getValue() . " skip: " . $Field->skipOnUpdate());
				
				$Field->validate ( $myContext );

				if ( $Field->skipOnUpdate ( ) ) continue ;
	// dois tipos de validação
	// 	1: erro e não pode continuar
	// 	2: erro e ignora este campo
				$Field->processUpdate();

	    		$fieldValuesList[] = $Field->getValue();
				$queryFieldList[$Field->getColumn()] = $Field->getValue();

			} catch (Exception $e) {
				$errorList[] = $e->getMessage ( );
			}

		}

		if ( !empty ( $errorList ) ) {
			// A LISTA DE ERROS ESTA CHEIA,
			// DEVEMOS:
			// 	VOLTAR AO FORM E EXIBIR TODAS AS MENSAGENS DE ERRO
			//  PARAR POR AQUI E NAO SALVAR NADA DO QUE FOI RECEBIDO VIA POST
		}

		$query = $view->queryBuilder()->getUpdateSaveQuery ( $view->getFields() );

// pr($fieldValuesList)		;
// prd($query);

// @todo precisa desacoplar. Chamar o $_POST dessa maneira tão engessada não é a melhor forma.
		foreach ( $primaryKeyFields as $pkField ) array_push ( $fieldValuesList , $_POST['TFFields'][$view->getName()][$myContext]['fields'][$pkField->getColumn()] );

		$this->_targetDb->prepare ( $query );

		$statement = $this->_targetDb->prepare ( $query );
		$exec = $statement->execute ( $fieldValuesList );
// pr($query);
// pr($fieldValuesList);

		if ( $exec ) {
			Feedback::addMessage( $this->_feedbackMessageList['success'][TotalFlex::CtxUpdate] , Feedback::MESSAGE_SUCCESS );
			// need redirect here to avoid user repost
			// maybe user will want to redirect to list page instead of keep on edit page
		} else {
			$errorInfo = $this->_targetDb->errorInfo();
			// pre($this->_targetDb->errorCode());
			Feedback::addMessage ( $errorInfo[2] , Feedback::MESSAGE_DANGER );
		}


    }

    private function _processCreate ( $viewName ) {

		$myContext = TotalFlex::CtxCreate ;
		$view      = $this->getView ( $viewName );
		if ( empty ( $_POST['TFFields'][$viewName][$myContext] ) ) return ;

		$fieldList = $view->getFieldsFromContext ( $myContext );

// DEVE COLOCAR A CHAMADA À VALIDAÇ˜AO DE CADA FIELD AQUI
// ** a validação deve:		
// * - validar todos os campos independente se um deles já não passou no teste
// * - retornar ao formulario para que o usuário possa editar e indicar todos os campos que não passaram com sua respectiva mensagem de erro
// * - passar o contexto para que a validação seja feita dentro de um contexto específico ex.:
// *		pode ser que um campo seja obrigatório na criação mas não na edição
// * 		os campos de validação já estão considerando que é necessário guardar o contexto conforme a classe FieldAbstract
// 			

		// extract field names and values
		$queryFieldList = array ( );
		foreach ( $fieldList as $Field ) {
			if ( !is_a ( $Field , 'TotalFlex\Field\Field' ) ) continue ;
			if ( $Field->isPrimaryKey() ) continue ;

if ( $Field->skipOnCreate ( ) ) continue ;

			$Field->processCreate();

			// $queryFieldList[$Field->getColumn()] = $Field->getValue();
			$columnList[] = $Field->getColumn();
			$valueList[] = $Field->getValue();
		}

		// now that we have all fields and it's values, let's get query to create and execute it
		$query     = $view->queryBuilder()->getCreateQuery($columnList);
		$statement = $this->_targetDb->prepare($query);
		$exec      = $statement->execute($valueList);

		if ( $exec ) {

			// if executed with success, let's set empty to all fields to clear form,
			foreach ( $fieldList as $Field ) {
				if ( !is_a ( $Field , 'TotalFlex\Field\Field' ) ) continue ;
				if ( $Field->isPrimaryKey() ) continue ;
				$Field->setValue('');
			}

			Feedback::addMessage( $this->_feedbackMessageList['success'][TotalFlex::CtxCreate] , Feedback::MESSAGE_SUCCESS );
			// need redirect here to avoid user repost
		} else {
			$errorInfo = $this->_targetDb->errorInfo();
			// pre($this->_targetDb->errorCode());
			Feedback::addMessage ( $errorInfo[2] , Feedback::MESSAGE_DANGER );
		}

    }

    /**
     * get the configuration and set data to field list
     */
    // public function setData ( &$View ) { // esse modelo (com '&') estava gerando um comportamento estranho. o objeto $View virava string misteriosamente
    public function setData ( $View ) {

		// check if we have at least one primary key field
		// if not, throw an exception
		// but sometimes we`ll need to hide the primary key field, because we don't want to show it

    	$query = $View->queryBuilder()->getUpdateGetQuery ( $View->getFields() );

// executar a query e alimentar os valores dos fields
// pr($query);

    	$statement = $this->_targetDb->prepare ( $query );
    	if ( !$statement->execute() ) throw new \Exception ( $statement->errorInfo() );
    	$result = $statement->fetchAll ( \PDO::FETCH_ASSOC );

    	if ( count ( $result ) > 1 ) throw new \Exception ( "Result can't be more than one row" );
    	if ( !count ( $result ) ) throw new \Exception ( "the data you want does not exist" );

    	$result = array_pop ( $result );

    	$fieldList = $View->getFields();

    	foreach ( $fieldList as &$Field ) {
    		if ( !is_a ( $Field , 'TotalFlex\Field\Field' ) ) continue ;
    		if ( $Field->skipOnSetData ( ) ) continue ;
    		$Field->setValue ( $result[$Field->getColumn()] );
    	}

    }

    /**
     * Generate a form or a grid for a given view to an given context using a given Formatter
     * @param  string $viewName  view name as string
     * @param  TotalFlex context $context   just one context at a time by call to this method
     * @param  string|object $Formatter formatter instance or a string with the name of the formatter we want to use
     * @return [type]            the output given by the formatter
     *   third parameter can be a string with the class name of TotalFlex\View\Formatter\FORMATTER_CLASS_NAME
     *   or can be an instance of the refered object. In case of it be an instance, you can instanciate the object and
     *   configure it as you want, for example: passing as a instance parameter the array of templates for html elements.
     *   if you don't need to configure it, you can pass just a string with the formatter you want to use
     */
    public function generate ( $viewName , $context , $Formatter = null ) {

    	if ( $Formatter === null ) $Formatter = self::$defaultFormatter ;
    	if ( !in_array ( $context , array ( TotalFlex::CtxNone , TotalFlex::CtxCreate , TotalFlex::CtxRead , TotalFlex::CtxUpdate , TotalFlex::CtxDelete ) ) ) throw new Exception\ManyContext ( "Please generate with one context at a time" );

    	if ( gettype ( $Formatter ) === 'string' ) {
    		$Formatter = '\\TotalFlex\\View\\Formatter\\' . ucfirst ( $Formatter );
    		$Formatter = new $Formatter();
    	}

    	$View = $this->getView ( $viewName );

		if ( $context === TotalFlex::CtxUpdate ) {
			$this->setData($View);
		}

		// output delivered is responsibility of the formatter object
    	return $Formatter->generate ( $View , $context );

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