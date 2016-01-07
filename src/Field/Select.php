<?php

namespace TotalFlex\Field;

use TotalFlex\Field\Field;

class Select extends Field {

	/**
	 * @var array options to put inside select
	 */
	protected $_options = array ( );

	// it can be required in future
	protected $_optionTemplate = "";

	/**
	 * @var string select specific default template
	 */
	protected static $defaultTemplate = "\t<select name=\"__name__\" id=\"__id__\" >\n__options__\n</select>\n\n" ;

	// php trigger an error because Select::getInstance has different number of agruments from Field::getInstance()
	// public static function getInstance ( $column , $label , $options ) {
	// 	return new self ( $column , $label , $options );
	// }

	/**
	 * Constructs the field
	 *
	 * @param string $column Field column name
	 * @param string $label Field label
	 * @throws \InvalidArgumentException
	 */
	public function __construct ( $column , $label , $options ) {

		parent::__construct ( $column , $label );
		$this->_options = $options ;

	}

    public function toHtml ( $context ) {

		$output     = $this->_encloseStart;

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