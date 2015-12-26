<?php

namespace TotalFlex\View\Formatter;
use TotalFlex\View\Formatter\ViewFormatterAbstract;
use TotalFlex\View\Formatter\ViewFormatterInterface;

class Html extends ViewFormatterAbstract implements ViewFormatterInterface {

	/**
	 * @var array The parsing queue
	 */
	// private $_queue = array ( );

	/**
	 * template content to generate output
	 * @var array
	 */
	public static $templateCollection = array (
		
		'start'   => "",
		'label'   => "\t<label for=\"__id__\">__label__</label><br>\n" ,
		'message' => "<div class=\"msg msg-__type__\">__message__</div>" ,
		'end'     => "" ,

		'form'    => array (
			'start' => "<form action=\"__action__\" method=\"__method__\" enctype=\"__enctype__\" >\n" ,
			'end'   => "</form>\n" ,
		),

		'input'      => array (
			'text'   => "\t<input type=\"__type__\" name=\"__name__\" id=\"__id__\" value=\"__value__\"/><br>\n\n" ,
			'hidden' => "\t<input type=\"hidden\" name=\"__name__\" id=\"__id__\" value=\"__value__\"/><br>\n\n" ,
			'file' 	 => "\t<input type=\"file\" name=\"__name__\" id=\"__id__\" value=\"__value__\"/><br>\n\n" ,
		),

		// O BOTAO NAO DEVE FICAR AQUI, DEVE SER CAPAZ DE SE AUTO-DESENHAR
		// 'button'  => array (
		// 	'submit' => "<button type=\"submit\" >__label__</button>" ,
		// 	'button' => "<button type=\"button\" >__label__</button>" ,
		// 	'cancel' => "<button type=\"cancel\" >__label__</button>" ,
		// ),

	);

	/**
	 * @inheritdoc
	 */
	public function __construct ( ) {

	}

	/**
	 * @inheritdoc
	 */
	public static function generate ( \TotalFlex\View $View , $context ) {
		
		$form = Html::$templateCollection['form']['start'];
		$form = str_replace ( "__method__"  , $View->getForm()->getMethod()  , $form ) ;
		$form = str_replace ( "__action__"  , $View->getForm()->getAction()  , $form ) ;
		$form = str_replace ( "__enctype__" , $View->getForm()->getEnctype() , $form ) ;

		// $form .= Html::generateField( \TotalFlex\Field\Hidden::getInstance ( "context" , null )->setValue($context) );
		// $form .= "<input type=\"hidden\" name=\"TFFields[".$View->getName()."][context]\" value=\"$context\" id=\"totalflex-context\" />\n";
		// $form .= "<input type=\"hidden\" name=\"TFFields[".$View->getName()."][view]\" value=\"".$View->getName()."\" id=\"totalflex-view\" />\n";

		foreach ($View->getFields() as $Field) {
			if (!$Field->isInContext($context)) continue;
			if ( is_a ( $Field , 'TotalFlex\Button' ) ) {
				$form .= Html::generateButton($Field,$context);
			} else {
				$form .= Html::generateField($Field,$context);
			}
		}

		$form .= Html::$templateCollection['form']['end'];

		return $form;

	}

	/**
	 * @inheritdoc
	 */
	// public function addField ( TotalFlex\Field\Field $Field ) {
	// // public function addField($id, $label, $type, $attributes = array ( )) {
	// 		// $field->getColumn(), 
	// 		// 	$field->getLabel(),
	// 		// 	$field->getType()

	// 	$this->_queue[] = ['field', $Field->getColumn() , $Field->getLabel() , $Field->getType() , $Field->getAttibutes() ];
	// }

	/**
	 * @inheritdoc
	 */
	// public function addMessage($message, $type) {
	// 	$this->_queue[] = ['message', $type];	
	// }

	/**
	 * Generate field HTML.
	 *
	 * @param string $id The field ID
	 * @param string $label Visual label to the field
	 * @param string $type HTML Input Type
	 * @param string $value Pre-filled value.
	 * @return string The field HTML
	 */
	protected static function generateField ( \TotalFlex\Field\Field $Field , $context ) {
		
		$output     = Html::$templateCollection['start'];

		if (!empty($Field->getLabel())) {
			$out = str_replace ( '__id__'    , $Field->getColumn() , Html::$templateCollection['label'] );
			$out = str_replace ( '__label__' , $Field->getLabel() , $out );
			$output .= $out;
		}

		$fieldTemplate = ( $Field->getTemplate () === null ) ? Html::$templateCollection['input'][$Field->getType()] : $Field->getTemplate () ;

		$attributeList = $Field->getAttributes ();
		$attributes = "";
		foreach ( $attributeList as $attrKey => $attrValue ) $attributes .= " $attrKey=\"$attrValue\" " ;

		$fieldTemplate = preg_replace ( '/^([^<]*<\w+)(\s*)(.*)/' , "$1 ".$attributes." $3" , $fieldTemplate);

		$out = str_replace ( '__type__'  , $Field->getType ()   , $fieldTemplate );
		$out = str_replace ( '__id__'    , "tf-field-".$Field->getColumn () , $out );
		$out = str_replace ( '__name__'  , "TFFields[".$Field->getView()->getName()."][$context][fields][".$Field->getPostKey ()."]" , $out );
		$out = str_replace ( '__value__' , $Field->getValue ()  , $out );
		$output .= $out ;

		$output .= Html::$templateCollection['end'];

		return $output;

	}

	/**
	 * Generate button HTML.
	 *
	 * @param string $id The field ID
	 * @param string $label Visual label to the field
	 * @param string $type HTML Input Type
	 * @param string $value Pre-filled value.
	 * @return string The field HTML
	 */
	protected static function generateButton ( \TotalFlex\Button $Button , $context ) {
		return "$Button" ;
	}

	/**
	 * Generate message HTML
	 *
	 * @param string $message The message
	 * @param int $type Message type.
	 * @return string The message HTML
	 */
	protected function _generateMessage($message, $type) {
		$output = str_replace ( 'type' , $type , Html::$templateCollection['message'] );
		$output = str_replace ( 'message' , $message , $output );
		return $output ;
	}

}
