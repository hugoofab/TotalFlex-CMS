<?php

namespace TotalFlex\Field;

use TotalFlex\Field\Field;

class SelectDB extends Field {

	/**
	 * @var array options to put inside select
	 */
	protected $_options = array ( );

	// it can be required in future
	protected $_optionTemplate = "";

	/**
	 * @var string if empty, we'll get the default template of \Field\Select
	 */
	protected static $defaultTemplate = "" ;

	public static function getInstance ( $column , $label , $dbSourceOrLabelField , $valueField , $query , $db = null ) {
		return new self ( $column , $label , $dbSourceOrLabelField , $valueField , $query , $db );
	}

	/**
	 * Constructs the field
	 *
	 * @param string $column Field column name
	 * @param string $label Field label
	 * @param mixed $dbSourceOrLabelField it can be an string with field name if you want to pass field label name and field value (key) name and query
	 * but you can make it passing a DBSource instance to $dbSourceOrLabelField param and ignoring another paramether in sequence
	 * @throws \InvalidArgumentException
	 */
	public function __construct ( $column , $label , $dbSourceOrLabelField , $valueField = "" , $query = "" , $db = null ) {

		if ( empty ( self::$defaultTemplate ) ) {
			self::$defaultTemplate = Select::getDefaultTemplate();
		}

		parent::__construct ( $column , $label );

		$dbSource = $dbSourceOrLabelField ;
		if ( !is_a ( $dbSource , 'DBSource' ) ) {
			$dbSource = new \TotalFlex\DBSource ( $dbSourceOrLabelField , $valueField , $query , $db );
		} 

		$this->_options = $dbSource->getLabelAsKeyFieldAsValue ( ) ;

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