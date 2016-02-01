<?php

namespace TotalFlex\Field;

use TotalFlex\Field\Field;

class ProtectedField extends Field {

	protected static $defaultEncloseStart  = "";
	protected static $defaultEncloseEnd    = "";
	
	protected static $defaultTemplate = "";
	protected static $defaultLabelTemplate = "";

	public static function getInstance ( $column , $value ) {
		$field = new ProtectedField ( $column , $column );
		$field->setValue( $value ) ;
		return $field;
	}

}