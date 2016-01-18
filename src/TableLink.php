<?php

namespace TotalFlex ;

class TableLink {


	protected $_tableSource = "" ; 
	protected $_fieldSource = "" ;
	protected $_tableTarget = "" ;
	protected $_fieldTarget = "" ;

	public static function getInstance ( $tableSource , $fieldSource , $tableTarget , $fieldTarget = null ) {
		return new self ( $tableSource , $fieldSource , $tableTarget , $fieldTarget );
	}

	/**
	 * @param string $target string with table name and column name in follow format: 'table.column'
	 * @param object $source DBSource instance
	 */
	public function __construct ( $tableSource , $fieldSource , $tableTarget , $fieldTarget = null ) {

		if ( $fieldTarget === null ) $fieldTarget = $fieldSource ;

		$this->_tableSource = $tableSource ;
		$this->_fieldSource = $fieldSource ;
		$this->_tableTarget = $tableTarget ;
		$this->_fieldTarget = $fieldTarget ;

		if ( is_a ( $source , 'DBSource' ) ) {

		}

	}


}