<?php

namespace TotalFlex ;

class DBSource {

	protected $_db          = null ;
	protected $_fieldKey    = "";
	protected $_fieldLabel  = "";
	protected $_arrayResult = array();

	public static function getInstance ( $fieldLabel , $fieldKey , $query , $db = null ) {
		return new self ( $fieldLabel , $fieldKey , $query , $db );
	}

	public function __construct ( $fieldLabel , $fieldKey , $query , $db = null ) {

		$this->_fieldKey   = $fieldKey ;
		$this->_fieldLabel = $fieldLabel ;

		if ( $db !== null ) {
			$this->_db = $db ;
		} else if ( \TotalFlex\TotalFlex::getDefaultDB () !== null ) {
			$this->_db = \TotalFlex\TotalFlex::getDefaultDB();
		} else {
			throw new DefaultDBNotSet ( "You have to set default db with TotalFlex::setDefaultDB() or give it as an argument in constructor method" );
		}

    	$statement = $this->_db->prepare ( $query );
    	if ( !$statement->execute() ) throw new Exception ( $statement->errorInfo() );
    	$this->_arrayResult = $statement->fetchAll ( \PDO::FETCH_ASSOC );

	}

	public function getFieldAsKeyLabelAsValue ( ) {
		$output = array ();
    	foreach ( $this->_arrayResult as $res ) {
    		$output[$res[$this->_fieldKey]] = $res[$this->_fieldLabel] ;
    	}
    	return $output ;
	}

	public function getLabelAsKeyFieldAsValue ( ) {
		$output = array ();
    	foreach ( $this->_arrayResult as $res ) {
    		$output[$res[$this->_fieldLabel]] = $res[$this->_fieldKey] ;
    	}
    	return $output ;
	}

	public function getFieldKey ( ) {
		return $this->_fieldKey;
	}

	public function getFieldLabel ( ) {
		return $this->_fieldLabel;
	}

}