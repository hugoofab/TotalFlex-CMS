<?php

namespace TotalFlex\View\Formatter;

class ViewFormatterAbstract {

	protected $Table 		= null ;

	public function setTable ( TotalFlex\Table $Table ) {
		$this->Table = $Table ;
	}

}