<?php

namespace TotalFlex\Field;

use TotalFlex\Field\Field;

class Number extends Field {

	public static function getInstance ( $column , $label ) {
		return new self ( $column , $label );
	}

    // public function setValue ( $value ) {
    	// $this->_value = preg_replace ( /[^\d\.]+/ , "" , $value );
    	// return $this ;
    // }


}