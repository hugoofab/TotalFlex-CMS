<?php

namespace TotalFlex\Field;

use TotalFlex\Field\Field;

class SelectMultiOptions extends Field {

	/**
	 * @var array options to put inside select
	 */
	protected $_options = array ( );

	/** @var PDO instance of PDO connected to the project database */
	protected $db = null ;

	// it can be required in future
	protected $_optionTemplate = "";

	/** @var integer instance id of this object */
	protected $_instanceId = 0 ;

	/** @var string DOM element id */
	protected $_elementId = "";

	/** @var integer instance count, will be incremented by 1 for each instance of this class */
	protected static $instanceCount = 0 ;

	/** @var string this attribute store the target table name where we'll save relationship  */
	protected $_targetTable = "";

	/** @var string fixed field name from target table  */
	protected $_fixedField  = "";

	/** @var string fixed field value referencing to main tuple */	
	protected $_fixedFieldValue  = "";

	/** @var string data from source table  */	
	protected $_source = array();

	/** @var string this is the field in targetTable that will store the seccond foreign key from _source */
	protected $_sourceLinkField = "";

	/** @var string label field from source table   */
	protected $_sourceLabel = null ;

	// // /** @var string  */
	// // protected $_fixedTargetId = "";

	// // protected $_fixedTargetIdValue = "";

	// protected $_source = array( );

	protected $_skipOnCreate = true ;
	protected $_skipOnUpdate = true ;
	protected $_skipOnSelect = true ;
	protected $_skipOnSetData = true ;
	/**
	 * @var string if empty, we'll get the default template of \Field\Select
	 */
	protected static $defaultTemplate = "

		<div class=\"navbar-form navbar-left\" >
			<div class=\"form-group\">
				<select class=\"form-control\" id=\"__selectid__\" >
					<option value=\"\">Selecione</option>
					\n__options__\n
				</select>
			</div>
			<button onclick=\"__btnaddclick__\" class=\"btn btn-primary\" id=\"__addbuttonid__\" type=\"button\" >Add</button>
		</div>	

		<table class=\"table table-select-multi-options\" id=\"__tableid__\">
			__tablehead__
			__existingoptions__
		</table>" ;
	
	/** @var string table head */
	protected $_tableHead = "
		<!-- tr>
			<td></td>
			<td></td>
		</tr -->";

	protected $_tableRowTemplate = "<tr id=\"__optionrowid__\">
		<td>__optionname__</td>
		<td width=\"1\">
			<input type=\"hidden\" name=\"__addedoptionname__\" id=\"__addedoptionid__\" value=\"__addedoptionvalue__\" >
			<button onclick=\"__btnremoveclick__\" data-id=\"__dataid__\" id=\"__removebuttonid__\" class=\"btn btn-xs btn-danger btn-default\" type=\"button\" ><i class=\"glyphicon glyphicon-remove\"></i></button>
		</td>
		</tr>";

	protected $_script = "<script>\n
		

		function __removefunctionname__ ( element ) {

			// id ( !confirm ( \"Tem certeza que deseja excluir?\" ) ) return false ;
	
			var dataId = element.getAttribute(\"data-id\");
			var rowId = \"__elementid__added-option-row-\" + dataId ;
			// var node = document.getElementById(rowId).style.display=\"none\" ;

			var node = document.getElementById(rowId) ;
			if (node.parentNode) {
				node.parentNode.removeChild(node);
			}

		}

		function __addfunctionname__ ( element ) {

			var selectOptionsId = \"__selectoptionsid__\" ;
			var instanceId = \"__instanceid__\";
			var mainTableId = \"__maintableid__\"

			var hiddenFieldName = \"TFFields[__viewname__][__context__][__elementid__][]\"

			var elementSelect = document.getElementById(selectOptionsId);
			var selectedOption = document.getElementById('smo-option-'+instanceId+'-'+elementSelect.value)

			if ( elementSelect.value === \"\" ) {
				alert(\"Favor selecione uma opção válida\");
				return ;
			}

			console.log(elementSelect.value)
			console.log(selectedOption.innerHTML)
	
			var newLine = \"<tr><td></td><td></td></tr>\"

			var newLine = \"<tr id=\\\"__optionrowid__\\\">\
			<td>\" + selectedOption.innerHTML + \"</td>\
			<td>\
				<input type=\\\"hidden\\\" name=\\\"\" + hiddenFieldName + \"\\\" id=\\\"LINHA-ADICIONADA-\" + elementSelect.value + \"\\\" value=\\\"\"+elementSelect.value+\"\\\" >\
				<button onclick=\\\"__btnremoveclick__\\\" id=\\\"__removebuttonid__\\\" class=\\\"btn btn-xs btn-danger btn-default\\\" type=\\\"button\\\" ><i class=\\\"glyphicon glyphicon-remove\\\"></i></button>\
			</td>\
			</tr>\";
			selectedOption.style.display = \"none\";
			elementSelect.value = \"\"; 
			document.getElementById(mainTableId).innerHTML += newLine

		}

		</script>";

	public static function getInstance ( $label , $targetTable , $fixedField , $fixedFieldValue , \TotalFlex\DBSource $dbSource ) {
		return new self ( $label , $targetTable , $fixedField , $fixedFieldValue , $dbSource );
	}

	/**
	 * Constructs the field
	 *
	 * @param string $column Field column name
	 * @param string $label Field label
	 * @throws \InvalidArgumentException
	 */
	public function __construct ( $label , $targetTable , $fixedField , $fixedFieldValue , \TotalFlex\DBSource $dbSource ) {

		$this->_targetTable     = $targetTable ;
		$this->_fixedField      = $fixedField ;
		$this->_fixedFieldValue = $fixedFieldValue ;
		$this->_source          = $dbSource ;
		$this->_instanceId      = ++self::$instanceCount;
		$this->_elementId       = str_replace ( "\\" , "_" , get_class ( $this ) ) . $this->_instanceId ;
		$this->_sourceLinkField = $dbSource->getFieldKey();
		$this->_sourceLabel     = $dbSource->getFieldLabel();

		if ( empty ( self::$defaultTemplate ) ) {
			self::$defaultTemplate = Select::getDefaultTemplate();
		}

		// parent::__construct ( $column , $label );
		if ( empty ( $this->_encloseStart  ) ) $this->_encloseStart  = static::$defaultEncloseStart ;
		if ( empty ( $this->_encloseEnd    ) ) $this->_encloseEnd    = static::$defaultEncloseEnd ;
		if ( empty ( $this->_template      ) ) $this->_template      = static::$defaultTemplate ;
		if ( empty ( $this->_labelTemplate ) ) $this->_labelTemplate = static::$defaultLabelTemplate ;

		$this
            // ->setColumn($column)
            ->setLabel($label)
            // ->setPostKey($column)
            ->setType('text')
            ->setPrimaryKey(false)
            ->setRules([])
            // ->setTableLink($tableLinkList)
        ;

		if ( \TotalFlex\TotalFlex::getDefaultDB () === null ) {
			throw new DefaultDBNotSet ( "You have to set default db with TotalFlex::setDefaultDB() or give it as an argument in constructor method" );
		}

		$this->db = \TotalFlex\TotalFlex::getDefaultDB();

		$this->_options = $dbSource->getFieldAsKeyLabelAsValue ( ) ;

	}

	/**
	 * in case of foreign key field name in relational table is different from source table primary key field
	 * @param [type] $sourceLinkField name of foreign key in target table
	 */
	public function setSourceLinkField ( $sourceLinkField ) {
		$this->_sourceLinkField = $sourceLinkField;
		return $this;
	}

	public function setData ( ) {

// RELACIONAMENTO N:N =========================================================================
// o TableLink com apenas um parâmetro significa que vai utilizar a própria configuração da view como source
// ->addField(Field\SelectMultiOptions::getInstance ( "Perfis de acesso do usuário" , "role_user" , array (
// 	// \TotalFlex\TableLink::getInstance ( "user" , "id_user" , "role_user" ), 
// 	\TotalFlex\TableLink::getInstance ( "role" , "id_role" , "role_user" ), 
// 	// \TotalFlex\TableLink::getInstance ( 'id_role' , 'id_role' )
// 	\TotalFlex\TableLink::getInstance ( \TotalFlex\DBSource::getInstance ( "description" , "id_role" , "SELECT id_role , description FROM role" ) , 'id_role' )	
// ) ) )
// ==========================================================================================

// RELACIONAMENTO N:N =========================================================================
// o TableLink com apenas um parâmetro significa que vai utilizar a própria configuração da view como source
		// ->addField(
		// 	Field\SelectMultiOptions::getInstance ( 
		// 		"Perfis de acesso do usuário" , // label
		// 		"role_user" , 					// target table 
		// 		"id_user" , 					// fixed target field
		// 		"5" , 							// value of fixed target field
		// 		\TotalFlex\DBSource::getInstance ( "description" , "id_role" , "SELECT id_role , description FROM role" ) // source
		// 	)
		// )
// ==========================================================================================
   // tabela   campo        campo       ent. relacio   campo 		  campo 	 tabela    campo
   // origem   origem       destino        		     destino      origem     dados     label
 // ( "USER" . "id_user" => "id_user" . "ROLE_USER" . "id_role" <= "id_role" . "ROLE" => "description" )
   // where id_user = x

// Field\SelectMultiOptions::getInstance

		// pegar todas as linhas adicionadas na entidade de relacionamento
		// pegar todas as opções na segunda tabela

		// $this->_tableLinkList;
		// pr($this->_view->getFields());

		// $mainFields = $this->_view->getFields();

		// $primaryKeyFields = $this->_view->getPrimaryKeyFields();

		// QUERY PARA TRAZER TODAS AS OPÇOES


		// QUERY PARA TRAZER TODOS OS ITENS ADICIONADOS ( ITENS DA TABELA )
		// "SELECT 
		// 	role.id_role , 
		// 	role.description
		// FROM role 
		// INNER JOIN role_user USING ( id_role )
		// WHERE role_user.id_user = ?
		// -- INNER JOIN role_user ON ( role.id_role = role_user.id_role )
		// "

		// "SELECT 
		// 	__table__.__field__ ,
		// 	__table__.__field__ 
		// FROM __table__ 
		// INNER JOIN __table__ ON|USING ( ************* ) 
		// WHERE __table__.__field__ == ????
		// "

		// foreach ( $primaryKeyFields as $key => $value ) {

		// }


		// foreach ( $mainFields as $Field ) {
		// 	if ( !$Field->isPrimaryKey ( ) ) continue ;
		// }


	}

	/**
	 * recuperar dados que foram adicionados
	 * @return [type] [description]
	 */
	private function getAddedData ( ) {

    	$query = "SELECT '' $this->_sourceLabel , $this->_sourceLinkField
    	FROM $this->_targetTable WHERE $this->_fixedField = $this->_fixedFieldValue " ;

    	$statement = $this->db->prepare ( $query );
    	if ( !$statement->execute ( ) ) throw new \Exception ( $statement->errorInfo() );
    	$arrayResult = $statement->fetchAll ( \PDO::FETCH_ASSOC );
    	foreach ( $arrayResult as &$res ) {
    		$res[$this->_sourceLabel] = $this->_options[$res[$this->_sourceLinkField]];
    		unset($this->_options[$res[$this->_sourceLinkField]]);
    	}

    	return $arrayResult ;
		
	}

	public function skipOnUpdate ( ) {
		return true ;
	}

	public function processUpdate ( ) {
	
		$viewName = $this->_view->getName();

		if ( empty ( $_POST['TFFields'][$viewName]["4"][$this->_elementId] ) ) return false ;

		$data     = $_POST['TFFields'][$viewName]["4"][$this->_elementId];
		$query    = "DELETE FROM `$this->_targetTable` WHERE `$this->_fixedField` = $this->_fixedFieldValue";
		$fieldKey = $this->_source->getFieldKey();
		$this->db->query ( $query );
		
		foreach ( $data as $dataId ) {
			$query = "INSERT INTO `$this->_targetTable` ( `$this->_fixedField` , `$fieldKey` ) VALUES ( '$this->_fixedFieldValue' , '$dataId' )"; 
			$this->db->query ( $query );
		}

	}


    public function toHtml ( $context ) {

		// $this->setData ( ) ;

		// ->addField(
		// 	Field\SelectMultiOptions::getInstance ( 
		// 		"Perfis de acesso do usuário" , // label
		// 		"role_user" , 					// target table 
		// 		"id_user" , 					// fixed target field
		// 		"5" , 							// value of fixed target field
		// 		\TotalFlex\DBSource::getInstance ( "description" , "id_role" , "SELECT id_role , description FROM role" ) // source
		// 	)
		// )

// $this->_sourceLinkField 
// 
     // = $targetTable ;
		// $fixedField       = $this->_fixedField      ;
		// $fixedFieldValue  = $this->_fixedFieldValue ;
// exit;
		
		$output             = $this->_encloseStart;
		$viewName           = $this->_view->getName();
		
		$DOM             = new \stdClass();
		$DOM->html       = new \stdClass();
		$DOM->javascript = new \stdClass();
		$DOM->html->selectOptionsId          = "tf-field-".$this->_elementId ;
		$DOM->html->mainTableId              = "table-".$this->_elementId ;
		$DOM->javascript->removeFunctionName = $this->_elementId."_"."REMOVE";
		$DOM->javascript->addFunctionName    = $this->_elementId."_"."ADD";
		
		$tableRowTemplate = $this->_tableRowTemplate;
		$tableRowTemplate = str_replace ( '__btnremoveclick__' , $DOM->javascript->removeFunctionName."(this)" , $tableRowTemplate ) ;


		// label
		if (!empty($this->getLabel())) {
			$out = str_replace ( '__id__'    , $DOM->html->selectOptionsId , $this->_labelTemplate );
			$out = str_replace ( '__label__' , $this->getLabel() , $out );
			$output .= $out;
		}

		$arrayResult   = $this->getAddedData ( );
		$fieldTemplate = $this->getTemplate () ;
		$options = "";

		// adicionar options disponiveis

// PRECISA REMOVER OS ITENS ADICIONADOS DOS ITENS DISPONIVEIS
// pr($this->_options);
// pr($arrayResult);

		foreach ( $this->_options as $value => $label ) {
			$selected = ( $value == $this->getValue() ) ? "selected" : "";
			$options .=   "<option id=\"smo-option-$this->_instanceId-$value\" $selected value=\"$value\">$label</option>" ;
		}

		$addedOptions = "";
		foreach ( $arrayResult as $addedOption ) {
			
			$option = str_replace ( "__optionname__" , $addedOption[$this->_sourceLabel] , $tableRowTemplate );
			$option = str_replace ( '__dataid__' , $addedOption[$this->_sourceLinkField] , $option );
			// $option = str_replace ( '__elementid__' , $this->_elementId , $option );
			$option = str_replace ( "__addedoptionname__"  , "TFFields[$viewName][$context][$this->_elementId][]" , $option );
			$option = str_replace ( "__addedoptionid__"    , "added-option-value-field-".$addedOption[$this->_sourceLinkField] , $option );
			$option = str_replace ( "__addedoptionvalue__" , $addedOption[$this->_sourceLinkField] , $option );
			$option = str_replace ( "__optionrowid__"      , $this->_elementId . "added-option-row-" . $addedOption[$this->_sourceLinkField] , $option );
			
			// $this->_sourceLinkField
			$option = str_replace ( "__removebuttonid__" , "btn-remove-".$this->_elementId."-".$addedOption[$this->_sourceLinkField] , $option );
			$addedOptions .= $option ;

		}

		// $out = str_replace ( '__type__'  , $this->getType ()   , $fieldTemplate );
		$out = str_replace ( '__selectid__'    , $DOM->html->selectOptionsId , $fieldTemplate );
		$out = str_replace ( '__addbuttonid__' , "button-".$this->_elementId   , $out );
		$out = str_replace ( '__tableid__'     , $DOM->html->mainTableId    , $out );

		$out = str_replace ( '__tablehead__'   , $this->_tableHead             , $out );

		$out = str_replace ( '__btnremoveclick__' , $DOM->javascript->removeFunctionName."()" , $out );
		$out = str_replace ( '__btnaddclick__'    , $DOM->javascript->addFunctionName."()"    , $out );

		$script = $this->_script ; 
		$script = str_replace ( '__removefunctionname__'    , $DOM->javascript->removeFunctionName , $script );

		$script = str_replace ( '__addfunctionname__' , $DOM->javascript->addFunctionName , $script );
		$script = str_replace ( '__selectoptionsid__' , $DOM->html->selectOptionsId       , $script );
		$script = str_replace ( '__instanceid__'      , $this->_instanceId                , $script );
		$script = str_replace ( '__maintableid__'     , $DOM->html->mainTableId           , $script );
		$script = str_replace ( '__viewname__'        , $viewName                         , $script );
		$script = str_replace ( '__context__'         , $context                          , $script );
		$script = str_replace ( '__elementid__'       , $this->_elementId                 , $script );

		// $script = str_replace ( '__elementid__'       , $this->_elementId                 , $script );


		// bigger markup should be last things to be processed
		$out = str_replace ( '__existingoptions__' , $addedOptions , $out );
		$out = str_replace ( "__options__" , $options , $out ) ;

		// $out = str_replace ( '__existingoptions__'   ,  EXISTING OPTIONS, WHERE ARE YOU??????           , $out           );
		// $out = str_replace ( '__name__'  , "TFFields[".$this->getView()->getName()."][$context][fields][".$this->getPostKey ()."]" , $out );
		// $out = str_replace ( '__value__' , $this->getValue ()  , $out );
		// $this->_tableRowTemplate


		$output .= $out ;
		$output .= $this->_encloseEnd;
		$output .= $script;

		return $output;

    }

    // public function setTableLink ( $tableLinkList ) {
    // 	$this->_tableLinkList = $tableLinkList ;
    // 	return $this;
    // }


}