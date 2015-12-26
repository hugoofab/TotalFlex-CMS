<?php

namespace TotalFlex\Field;

use TotalFlex\Field\Field;

class Hidden extends Field { 


	public static function getInstance ( $column , $label ) {
		return new Hidden ( $column , $label );
	}

	public function getLabel ( ) {
		return "";
	}

	public function getType ( ) {
		return "hidden";
	}

	public function getTemplate ( ){
		return "<input type=\"hidden\" name=\"__name__\" value=\"__value__\" id=\"__id__\" />\n";
	}

}