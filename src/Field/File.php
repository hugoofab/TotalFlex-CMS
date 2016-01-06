<?php

namespace TotalFlex\Field;

use TotalFlex\Field\Field;

class File extends Field {

	private $_template = "\t<input type=\"file\" name=\"__name__\" id=\"__id__\" value=\"__value__\"/><br>\n\n";

	public static function getInstance ( $column , $label ) {
		return new File ( $column , $label ) ;
	}

	public function getType ( ) {
		return "file";
	}

}