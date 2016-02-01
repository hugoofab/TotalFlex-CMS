<?php

namespace TotalFlex\Field;

use TotalFlex\Field\Field;

class Date extends Field {

	public static function getInstance ( $column , $label ) {
		return new self ( $column , $label );
	}

}