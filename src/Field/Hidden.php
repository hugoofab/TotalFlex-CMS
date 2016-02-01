<?php

namespace TotalFlex\Field;

use TotalFlex\Field\Field;

class Hidden extends Field {


	protected static $defaultEncloseStart  = "";
	protected static $defaultEncloseEnd    = "";
	
	protected static $defaultTemplate = "\t<input type=\"hidden\" name=\"__name__\" id=\"__id__\" value=\"__value__\"/>\n\n";
	protected static $defaultLabelTemplate = "";

	public static function getInstance ( $column ) {
		return new Hidden ( $column , $column );
	}

	public function getLabel ( ) {
		return "" ;
	}

	public function getType ( ) {
		return "hidden";
	}

	public function getTemplate ( ){
		return $this->_template;
	}

}