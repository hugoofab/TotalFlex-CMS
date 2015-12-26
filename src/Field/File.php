<?php

namespace TotalFlex\Field;

use TotalFlex\Field\Field;

class File extends Field { 

	public static function getInstance ( $column , $label ) {
		return new File ( $column , $label ) ;
	}

	public function getType ( ) {
		return "file";
	}

}