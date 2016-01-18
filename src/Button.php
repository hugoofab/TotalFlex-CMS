<?php

namespace TotalFlex;

class Button {

	// CONFIRMADOS NA NOVA VERSÃO
	/**
	 * @var array[Context] The contexts this fields is allowed
	 */
	private $_contexts;

	protected $_label          = "" ;

	protected $_attributes     = array ( ) ;

	protected $_buttonType     = "button";

	/**
	 * @var [integer] table row id
	 */
	protected $_rowID          = "" ;

    // NECESSÁRIOS MAS NÃO CONFIRMADOS
	protected $disableIf_list = array ( );
    protected $elementId ;

	// PRECISA DISSO?
	protected $cellParams     = array ( ) ;
	protected $data ;

	protected static $defaultTemplate = "<button __attributeset__ >__content__</button>";

	protected $_template = "" ;

	public static function getInstance ( $label , $attributes = array ( ) ) {
		return new self ( $label , $attributes );
	}

    public function __construct ( $label , Array $attributes = array () ) {
		$this->setLabel($label) ;
		$this->_attributes   = $attributes ;
		$this->_template   = self::$defaultTemplate;
    }

    public static function setDefaultTemplate ( $defaultTemplate ) {
    	self::$defaultTemplate = $defaultTemplate ;
    }    

    /**
     * set one attribute
     * @param [type] $key   [description]
     * @param string $value [description]
     */
    public function setAttribute ( $key , $value = "" ) {
        $this->_attributes[$key] = $value;
        return $this;
    }

    /**
     * get one attribute
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function getAttribute ( $key ) {
    	return $this->_attributes[$key] ;
    }

    /**
     * set whole attribute array
     * @param [type] $attributes [description]
     */
    public function setAttributes ( $attributes ) {
    	$this->_attributes = $attributes ;
    	return $this ;
    }

    /**
     * return whole attribute array
     * @return [type] [description]
     */
    public function getAttributes ( ) {
    	return $this->_attributes;
    }

    /**
     * Gets the Field label
     *
     * @return string Field label
     */
    public function getLabel() {
        return $this->_label;
    }

    /**
     * Sets the Field label
     *
     * @param string Field label
     * @return self
     */
    public function setLabel($label) {
        $this->_label = $label;
        return $this;
    }

    /**
     * Gets the contexts this fields is allowed
     *
     * @return int The contexts this fields is allowed
     */
    public function getContexts() {
        return $this->_contexts;
    }

    public function isInContext($context) {
        return (($context & $this->getContexts()) !== 0);
    }

    // public static function getInstance ( $label , $class = "" ) {
    //     $instance = new Button ( $label , $class ) ;
    //     return $instance ;
    // }

    public function toHtml ( ) {

        $attributeSetString = '';

        $attributeSet = array (
            'type'           => $this->_buttonType ,
            'data-row-id'    => $this->_rowID
        ) ;

        foreach ( $this->cellParams as $cellParam ) {
            $attributeSet[strtolower($cellParam)] = $this->data[$cellParam] ;
        }

        if ( $this->isDisabled ( ) ) {
            $this->_attributes['disabled'] = 'disabled';
        } else if ( isset ( $this->_attributes['disabled'] ) ) {
            unset ( $this->_attributes['disabled'] );
        }

        foreach ( $this->_attributes as $key => $value ) $attributeSet[$key] = $value ;
        foreach ( $attributeSet as $key => $value )     $attributeSetString .= " $key=\"$value\"" ;

        $output =
            "<button $attributeSetString>" .
                $this->getLabel() .
            "</button>\n"
        ;

        return $output;

    }


    public function isDisabled ( ) {

        $data = $this->data ; // só para conveniencia do programador ;)

        foreach ( $this->disableIf_list as $condition ) {
            if ( eval ( "return ( $condition ) ; " ) ) return true ;
        }
        return false ;
    }

    /**
     * set table row id associated with this button
     * @param [type] $id [description]
     */
    public function setRowID ( $id ) {
        $this->_rowID = $id ;
        return $this;
    }

    // public function addDisableIf ( $condition ) {
    //     $this->disableIf_list[] = $condition ;
    //     return $this;
    // }

    // public function addStyleIf ( $condition , $style ) {

    // }

    // public function setData ( $data , $paramList = array ( ) ) {
    //     $this->cellParams = $paramList;
    //     $this->data = $data ;
    //     return $this;
    // }

    // public function onClick ( $function ) {
    //     $this->onClick = $function;
    //     return $this;
    // }

    // public function align ( $direction ) {
    //     return $this->addStyle ( "float:$direction" );
    // }

    // public function addStyle ( $style ) {
    //     $styleList = explode ( ";" , $this->style );
    //     $styleList[] = $style ;
    //     $this->style = implode ( ";" , $styleList );
    //     return $this;
    // }

    /**
     * Sets the contexts this fields is allowed
     *
     * @param int The contexts this fields is allowed. See TotalFlex::Ctx* constants.
     * @return self
     */
    public function setContexts($contexts) {
        $this->_contexts = $contexts;
        return $this;
    }

    public function setType ( $type ) {
        $this->buttonType = $type ;
        return $this;
    }



}
