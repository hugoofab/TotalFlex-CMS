<?php

namespace TotalFlex;

class Feedback {

	public static $messageList = array ( );
	
	// essa mensagem deveria piscar quado criada para ver que houve uma mudança entre
	// a visualização de uma antiga e uma nova
	public static $templateList = array (
		"<p class=\"bg-danger\">__message__</p>" ,
		"<p class=\"bg-primary\">__message__</p>" ,
		"<p class=\"bg-success\">__message__</p>" ,
		"<p class=\"bg-info\">__message__</p>" ,
		"<p class=\"bg-warning\">__message__</p>"
	);

	const MESSAGE_ERROR   = 0;
	const MESSAGE_DANGER  = 0;
	const MESSAGE_PRIMARY = 1;
	const MESSAGE_SUCESS  = 2;
	const MESSAGE_INFO    = 3;
	const MESSAGE_WARNING = 4;

	public static function addMessage ( $message , $type = Feedback::MESSAGE_INFO ) {
		
		if ( !isset ( Feedback::$templateList[$type] ) ) throw new Exception ( "undefined message error type" );
		Feedback::$messageList[] = str_replace ( "__message__" , $message , Feedback::$templateList[$type] );

	}

	/**
	 * precisa implementar
	 * @return [type] [description]
	 */
	public static function dumpMessages ( ) {
		if ( empty ( Feedback::$messageList ) ) return ;
		$output = "";
		foreach ( Feedback::$messageList as $message ) $output .= $message ; 
		return $output ;
	}

	public static function formatMessage ( ) {

	}

}