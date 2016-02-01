<?php

namespace TotalFlex\Field;

use TotalFlex\Field\Field;

class Textarea extends Field {

	protected $_maxLength ;

	protected static $defaultTemplate = "\t<textarea name=\"__name__\" id=\"__id__\">__value__</textarea><br>\n\n";
	protected static $defaultLabelTemplate = "\t<label for=\"__id__\">__label__</label><br>\n" ;

	public static function getInstance ( $column , $label ) {
		return new Textarea ( $column , $label );
	}


}