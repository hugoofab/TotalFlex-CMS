<?php

namespace TotalFlex;


use TotalFlex\Exception\InvalidField;
use TotalFlex\Exception\AlreadyRegisteredField;

class View {
	/**
	 * @var string View name
	 */
	private $_name;

	/**
	 * @var array View fields
	 */
	private $_fields;

    /**
     * @var callable Callback to execute before inserts on this View
     */
    private $_preCreationCallback;

    /**
     * @var callable Callback to execute after inserts on this View
     */
    private $_postCreationCallback;

    /**
     * @var TotalFlex\QueryBuilder, is a query builder object
     */
    private $_queryBuilder ;

    /**
     * @var default field contexts for this View
     */
    private $_contexts;

    /** @var function filterAction is a function that will be invoked before anything */
    private $_filterAction ;

    private $_form ;

    /**
     * Constructs the View
     *
     * @param string $name The View name
     */
    public function __construct($name) {
        $this->setName($name);
        $this->setContexts ( TotalFlex::CtxCreate|TotalFlex::CtxRead|TotalFlex::CtxUpdate|TotalFlex::CtxDelete );
        $this->_queryBuilder = new QueryBuilder();
        $this->_form = new Form();
    }

    public function getForm ( ) {
    	return $this->_form;
    }

    public function setForm ( $form ) {
    	$this->_form = $form ;
    	return $this ;
    }

    /**
     * set query builder for this view
     * @param TotalFlex\QueryBuilder $QueryBuilder [description]
     */
    // public function setQuery ( \TotalFlex\QueryBuilder $QueryBuilder ) {
    // 	$this->_queryBuilder = $QueryBuilder ;
    // 	return $this;
    // }

    /**
     * Sets the default field contexts for this View
     *
     * @param int The contexts this fields is allowed. See TotalFlex::Ctx* constants.
     * @return self
     */
    public function setContexts($contexts = TotalFlex::CtxNone) {
        $this->_contexts = $contexts;
        return $this;
    }

    /**
     * Gets the View name
     *
     * @return string View name
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Sets the View name
     *
     * @param string View name
     * @return self
     */
    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    /**
     * Gets the View fields
     * @todo Defensive copy fields before returning it
     * @return array View fields
     */
    public function getFields() {
        return $this->_fields;
    }

    public function getFieldsFromContext ( $context ) {
    	$output = array ();
    	foreach ($this->_fields as $Field) {
    		if ( $Field->isInContext($context) ) {
    			$output[] = $Field ;
    		}
    	}
    	return $output ;
    }

    /**
     * Gets the callback to execute before inserts on this View
     * @return callable|null The callback to execute before inserts on this View
     */
    public function getPreCreationCallback() {
        return $this->_preCreationCallback;
    }

    /**
     * Sets the callback to execute before inserts on this View
     *
     * @param callable|null $callback The callback to execute before inserts on this View
     * @return self
     */
    public function setPreCreationCallback($callback) {
        $this->_preCreationCallback = $callback;
        return $this;
    }

    /**
     * Executes the callback before inserts on this View
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
     * Gets the callback to execute after inserts on this View
     * @return callable|null The callback to execute after inserts on this View
     */
    public function getPostCreationCallback() {
        return $this->_postCreationCallback;
    }

    /**
     * Sets the callback to execute after inserts on this View
     *
     * @param callable|null  $callbackThe callback to execute after inserts on this View
     * @return self
     */
    public function setPostCreationCallback($callback) {
        $this->_postCreationCallback = $callback;
        return $this;
    }

    /**
     * Executes the callback after inserts on this View
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

    public function getPrimaryKeyFields ( ) {

    	$output = array ( );
    	foreach ( $this->_fields as $Field ) {
    		if ( !is_a ( $Field , 'TotalFlex\Field\Field' ) ) continue ;
    		if ( $Field->isPrimaryKey ( ) ) {
    			$output[] = $Field;
    		}
    	}

    	return $output ;

    }

    /**
     * Add a field to the View
     *
     * @param string $Field The field column name or field Object
     * @param string $columnLabel The field label
     * @return TotalFlex\Field The created field
     * @throws TotalFlex\Exception\InvalidField
     * @throws TotalFlex\Exception\AlreadyRegisteredField
     */
    public function addField( $Field , $columnLabel = null ) {

		if ( !is_a ( $Field , 'TotalFlex\Field\Field' ) ) {
			if ( $columnLabel === null ) $columnLabel = $Field;
	    	$Field = new \TotalFlex\Field\Field ( $Field , $columnLabel );
		}

    	// field defaults
		$Field->setContexts ( $this->_contexts );
		$Field->setView ( $this );

		if ( $Field->isInContext ( TotalFlex::CtxCreate ) && isset ( $_POST['TFFields'][$this->getName()][TotalFlex::CtxCreate]['fields'][$Field->getColumn()] ) ) {
			$Field->setValue ( $_POST['TFFields'][$this->getName()][TotalFlex::CtxCreate]['fields'][$Field->getColumn()] );
		}

		if ( $Field->isInContext ( TotalFlex::CtxUpdate ) && isset ( $_POST['TFFields'][$this->getName()][TotalFlex::CtxUpdate]['fields'][$Field->getColumn()] ) ) {
			$Field->setValue ( $_POST['TFFields'][$this->getName()][TotalFlex::CtxUpdate]['fields'][$Field->getColumn()] );
		}

		if ( $Field->isInContext ( TotalFlex::CtxRead ) && isset ( $_POST['TFFields'][$this->getName()][TotalFlex::CtxRead]['fields'][$Field->getColumn()] ) ) {
			$Field->setValue ( $_POST['TFFields'][$this->getName()][TotalFlex::CtxRead]['fields'][$Field->getColumn()] );
		}

		if ( empty ( $Field->getValue ( ) ) ) $Field->setValue ( $Field->getEmptyValue ( ) ) ;

		if ( is_a ( $Field , '\TotalFlex\Field\File' ) ) {
			$this->getForm()->setEnctype("multipart/form-data");
		}

		// append it!
		$this->_fields[] = $Field ;

		return $this;
    }

    /**
     * return last field added
     * @return [type] [description]
     */
    private function getLastField ( ) {
    	$fieldKey = -1 + count ( $this->_fields ) ;
    	return $this->_fields[$fieldKey];
    }

    /**
     * [addButton description]
     */
    public function addButton ( \TotalFlex\Button $Button ) {
    	$Button->setContexts ( $this->_contexts );
    	$this->_fields[] = $Button ;
    	return $this ;
    }

    /**
     * call functino callback 
     * @param  [type] $post [description]
     * @return [type]       [description]
     */
    public function preFilter ( $context ) {
    	$filterAction = $this->_filterAction ;
		if ( gettype ( $filterAction ) === "object" ) {
			return $filterAction ( $context , $this , TotalFlex::getDefaultDB() );
		} else {
			return true ;
		}
    }

    /**
     * add a function callback to be called before anything
     * @param [type] $filterAction [description]
     */
    public function addFilter ( $filterAction ) {

		if ( gettype ( $filterAction ) === "object" ) {
			$this->_filterAction = $filterAction ;
		}

		return $this ;

    }

    public function addElement ( \TotalFlex\Html $Element ) {
    	$Element->setContexts ( $this->_contexts );
    	$this->_fields[] = $Element ;
    	return $this ;
    }

    /**
     * THIS METHOD IS A PIPE TO FIELD OBJECT
     * set last added field as primary key
     * @param boolean $value [description]
     */
    public function setPrimaryKey ( $value = true ) {
    	$this->getLastField()->setPrimaryKey ( $value );
    	$this->getLastField()->setAttribute ('disabled','disabled');
    	return $this;
    }

    /**
     * THIS METHOD IS A PIPE TO FIELD OBJECT
     * Adds a rule to the ruleset of this field
     *
     * @param IRule $rule The rule
     * @return self
     */
    public function addRule($rule) {
        $this->getLastField()->addRule ($rule);
        return $this;
    }

    /**
     * THIS METHOD IS A PIPE TO FIELD OBJECT
     * [setFieldTemplate description]
     * @param [type] $template [description]
     */
    public function setFieldTemplate ( $template ) {
    	$this->getLastField()->setTemplate ( $template );
    	return $this ;
    }

    /**
     * THIS METHOD IS A PIPE TO QUERYBUILDER OBJECT
     * [setTable description]
     * @param [type] $tableName [description]
     */
    public function setTable ( $tableName ) {
    	$this->_queryBuilder->from ( $tableName ) ;
    	return $this ;
    }

    public function queryBuilder ( ) {
    	return $this->_queryBuilder ;
    }

    // public function getTable ( ) {
    // 	return $this->_queryBuilder->from ( $tableName ) ;
    // }

    /**
     * THIS METHOD IS A PIPE TO QUERYBUILDER OBJECT
     * @param [type] $tableDefinition [description]
     */
    public function innerJoin ( $tableDefinition ) {
    	$this->_queryBuilder->innerJoin ( $tableDefinition ) ;
    	return $this ;
    }

    /**
     * THIS METHOD IS A PIPE TO QUERYBUILDER OBJECT
     * @param [type] $tableDefinition [description]
     */
    public function leftJoin ( $tableDefinition ) {
    	$this->_queryBuilder->leftJoin ( $tableDefinition ) ;
    	return $this ;
    }

    /**
     * THIS METHOD IS A PIPE TO QUERYBUILDER OBJECT
     * @param [type] $tableDefinition [description]
     */
    public function rightJoin ( $tableDefinition ) {
    	$this->_queryBuilder->rightJoin ( $tableDefinition ) ;
    	return $this ;
    }

    /**
     * THIS METHOD IS A PIPE TO QUERYBUILDER OBJECT
     * @param [type] $tableDefinition [description]
     */
    public function join ( $tableDefinition ) {
    	$this->_queryBuilder->join ( $tableDefinition ) ;
    	return $this ;
    }

    /**
     * THIS METHOD IS A PIPE TO QUERYBUILDER OBJECT
     * @param [type] $condition [description]
     */
    public function where ( $condition ) {
    	$this->_queryBuilder->where ( $condition ) ;
    	return $this ;
    }

}