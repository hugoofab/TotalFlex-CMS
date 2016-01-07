<?php

namespace TotalFlex\Field;

use TotalFlex\Field\Field;

class SelectDB extends Field {

	/**
	 * @var array options to put inside select
	 */
	protected $_options = array ( );

	protected $db = null ;

	// it can be required in future
	protected $_optionTemplate = "";

	/**
	 * @var string if empty, we'll get the default template of \Field\Select
	 */
	protected static $defaultTemplate = "" ;

	public static function getInstance ( $column , $label , $labelField , $valueField , $query , $db = null ) {
		return new self ( $column , $label , $labelField , $valueField , $query , $db );
	}

	/**
	 * Constructs the field
	 *
	 * @param string $column Field column name
	 * @param string $label Field label
	 * @throws \InvalidArgumentException
	 */
	public function __construct ( $column , $label , $labelField , $valueField , $query , $db = null ) {

		if ( empty ( self::$defaultTemplate ) ) {
			self::$defaultTemplate = Select::getDefaultTemplate();
		}

		parent::__construct ( $column , $label );

		if ( $db !== null ) {
			$this->db = $db ;
		} else if ( \TotalFlex\TotalFlex::getDefaultDB () !== null ) {
			$this->db = \TotalFlex\TotalFlex::getDefaultDB();
		} else {
			throw new DefaultDBNotSet ( "You have to set default db with TotalFlex::setDefaultDB() or give it as an argument in constructor method" );
		}

    	$statement = $this->db->prepare ( $query );
    	if ( !$statement->execute() ) throw new Exception ( $statement->errorInfo() );
    	$result = $statement->fetchAll ( \PDO::FETCH_ASSOC );

    	foreach ( $result as $res ) {
    		$this->_options[$res[$labelField]] = $res[$valueField] ;
    	}

	}

    public function toHtml ( $context ) {

		$output = $this->_encloseStart;

		if (!empty($this->getLabel())) {
			$out = str_replace ( '__id__'    , $this->getColumn() , $this->_labelTemplate );
			$out = str_replace ( '__label__' , $this->getLabel() , $out );
			$output .= $out;
		}

		$attributeList = $this->getAttributes ();
		$attributes = "";
		foreach ( $attributeList as $attrKey => $attrValue ) $attributes .= " $attrKey=\"$attrValue\" " ;

		$fieldTemplate = preg_replace ( '/^([^<]*<\w+)(\s*)(.*)/' , "$1 ".$attributes." $3" , $this->getTemplate () );

		$out = str_replace ( '__type__'  , $this->getType ()   , $fieldTemplate );
		$out = str_replace ( '__id__'    , "tf-field-".$this->getColumn () , $out );
		$out = str_replace ( '__name__'  , "TFFields[".$this->getView()->getName()."][$context][fields][".$this->getPostKey ()."]" , $out );
		$out = str_replace ( '__value__' , $this->getValue ()  , $out );
		$output .= $out ;

		$options = "";
		foreach ( $this->_options as $label => $value ) {
			$selected = ( $value == $this->getValue() ) ? "selected" : "";
			$options .=   "<option $selected value=\"$value\">$label</option>" ;
		}

		$output = str_replace ( "__options__" , $options , $output ) ;

		$output .= $this->_encloseEnd;

		return $output;

    }

}