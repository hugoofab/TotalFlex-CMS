<?php 

define ( 'DEBUG' , isset ( $_SERVER['HTTP_DEBUG'] ) ? ($_SERVER['HTTP_DEBUG'] === 'DEBUG_A51ADF651ASDF51SFGG') : false ) ;
define ( 'SHOW_SQL_QUERIES' , isset ( $_SERVER['HTTP_SHOW_SQL_QUERIES'] )? strpos ( $_SERVER['HTTP_SHOW_SQL_QUERIES'] , 'DEBUG_A51ADF651ASDF51SFGG' ) : false ) ;


// pr die
function prd ( ) {
	if ( !defined ( 'DEBUG' ) || DEBUG === false ) return ;
	$backTrace = debug_backtrace ();
	$varList   = func_get_args ( );
	_pr ( $varList , "#0F0" , "#000" , $backTrace ) ;
	exit;
}

// pr error
function pre ( ) {
	$backTrace = debug_backtrace ();
	$varList   = func_get_args ( );
	_pr ( $varList , "#FFF" , "#8B0000" ,  $backTrace ) ;
}

// pr success
function prs ( ) {
	$backTrace = debug_backtrace ();
	$varList   = func_get_args ( );
	_pr ( $varList , "#FFF" , "#005F08" , $backTrace ) ;
}

function pr ( ) {
	$varList   = func_get_args ( );
	$backTrace = debug_backtrace ();
	_pr ( $varList , "#0F0" , "#000" , $backTrace );
}

function _pr ( $varList = "" , $foreground = "#0F0" , $background = "#000" , $backTrace = false ) {

    if ( !defined ( 'DEBUG' ) || DEBUG === false ) return ;

    if ( $backTrace === false ) $backTrace = debug_backtrace ();

    $options = array(
        'File' => $backTrace[0]['file'] ,
        'Line' => $backTrace[0]['line']
    );
	
	$file = $options['File'];
	$line = $options['Line'];
	$id   = md5 ( print_r ( $varList , true ) . rand ( 0 , 100 ) ) ;

	if ( !empty ( $varList ) ) {

		foreach ( $varList as $var ) {

			echo "<pre id=\"$id\" class='hf_debug' style=\"font-size:12px;line-height:1em;background:${background};color:${foreground};position:relative;z-index:99999;filter:alpha(opacity=80); -moz-opacity:0.80; opacity:0.80;font-family:courier new;white-space: pre-wrap;\">Type: " . gettype ( $var ) . "\n" ;

			if ( gettype ( $var ) == 'boolean' ) {
	            echo ( $var ) ? "TRUE" : "FALSE" ;
			} else {
				print_r ( $var );
			}

	        echo "<hr>";

		}

	} else {
		echo "<pre id=\"$id\" class='hf_debug' style=\"font-size:12px;line-height:1em;background:${background};color:${foreground};position:relative;z-index:99999;filter:alpha(opacity=80); -moz-opacity:0.80; opacity:0.80;font-family:courier new;white-space: pre-wrap;\">\n" ;
	}

    array_shift ( $backTrace ) ;
    $backTrace = array_reverse ( $backTrace );

    foreach( $backTrace as $key => $bt ) {
    	foreach ( $bt['args'] as &$arg ) if ( gettype ( $arg ) === 'object' ) $arg = "Object of " . get_class($arg) ;
    	$implode = @implode ( "] , [" , $bt['args'] ) ;
        $function = $bt['function'] . " ( [" . $implode . "] ) " ;
        echo "\n<span style=\"margin-top:3px;padding-left:4px;background:#070;color:#000;font-weight:bold;\">" . @$bt['file'] . ":" . @$bt['line'] . "&nbsp;</span> -&gt;" . $function ;
    }
	echo "\n<span style=\"margin-top:3px;padding-left:4px;background:#0F0;color:#000;font-weight:bold;line-height:1.5em;\">" . $file . ":" . $line . "&nbsp;&nbsp;&nbsp;<a style=\"color:#FFF;background:#000;padding-left:5px;\" onclick=\"document.getElementById('$id').innerHTML=''\" href=\"javascript:;\">fechar este &nbsp;&nbsp;</a><a onclick=\"$('.hf_debug').hide()\" style=\"color:#FFF;background:#000;padding-left:5px;\" href=\"javascript:;\">fechar todos</a></span></pre>" ;

}