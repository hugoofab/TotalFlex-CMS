<?

require 'vendor/autoload.php';
require 'bootstrap.php';


use TotalFlex\TotalFlex;
use TotalFlex\Rule;
use TotalFlex\Button;
use TotalFlex\Field;
// use TotalFlex\Field\;
// use TotalFlex\Rule\Required;
// use TotalFlex\Rule\Length;

/************************************************************
 * Creating fake business database
 * ---------------------------------------------------------
 * It's not supposed to be anywhere in the code. Creating
 * here to show the infraestructure expected from the 
 * target application.
 *************************************************************/
$conn = new PDO('sqlite:business.db3');
$conn->query("CREATE TABLE IF NOT EXISTS business_entity (id_be INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT)");
$conn = null;

/************************************************************
 * Bootstraping TotalFlex
 * ---------------------------------------------------------
 * This code is needed every initialization of the 
 * application. There is, in te future, ideas to make this
 * part of one simple SQLite database that will only need
 * one line to bootstrap it all.
 *************************************************************/

// Initializing Total Flex with callback url/method and the target database connection info
// 'index.php?callback=1', 'POST', 'sqlite:business.db3'

// 10*40*
// $pdo = new FluentPDOPDO("mysql:dbname=fazerbrasil", "root", "");
$pdo = new PDO("mysql:dbname=fazerbrasil", "root", "");

$TotalFlex = new TotalFlex ( $pdo );

// DEFAULT TEMPLATE #################################################################
	\TotalFlex\View\Formatter\Html::$templateCollection['start']         = "<div class=\"col-md-6 form-group\">\n";
	\TotalFlex\View\Formatter\Html::$templateCollection['end']           = "</div>\n";
	\TotalFlex\View\Formatter\Html::$templateCollection['input']['text'] = "\t<input class=\"form-control\" type=\"__type__\" name=\"__name__\" id=\"__id__\" value=\"__value__\"/><br>\n\n";
// ##################################################################################

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet" />
	
	<style>
		*,html,body{font-size: 1em;}
	</style>

</head>
<body>

<?php

// Registering table `business_entity` with its fields
$TotalFlex->registerView('business_entity','business_entity_alias')

	// set default contexts to be applyed to all fields from now on
	->setContexts(TotalFlex::CtxCreate)

	->addField('id_be','ID')
		->setPrimaryKey()

	->addField( new Field\Text ( 'name' , 'Name (com new)' )  )

	->addField('name','Name')
		->addRule(new Rule\Required())
		->addRule(new Rule\Length(10, 20))

	->setContexts(TotalFlex::CtxUpdate|TotalFlex::CtxCreate)

	->addField('image','Image')
	
	->addField('url','URL')
		->setFieldTemplate ( "\tHTTP://<input class=\"form-control\" type=\"__type__\" name=\"__name__\" id=\"__id__\" value=\"__value__\"/><br>\n\n" )

	->addField('short_description','Descrição curta')
	
	->addField('description','Descrição')
		->setFieldTemplate ( "\t<textarea style=\"width:100%;height:200px;\" class=\"form-control\" name=\"__name__\" id=\"__id__\" >__value__</textarea><br>\n\n" )

	->addButton ( new Button ( "Salvar" , array ( 'class' => "btn btn-primary" , "type" => "submit" ) ) )

	->setTable ( "business_entity" )


	// PRE INSERT CALLBACK
	->setPreCreationCallback(function($creationValues) {
		print_r("Inserting values into database: ");
		print_r($creationValues);
	})

	// POST INSERT CALLBACK
	->setPostCreationCallback(function($creationValues) {
		print_r("Inserted values into database: ");
		print_r($creationValues);
	});

/************************************************************
 * TotalFlex Use Case
 * ---------------------------------------------------------
 * This is the real code to generate some form with TotalFlex
 * AND handle the return. 
 *************************************************************/
// $showForm = true;

// if (isset($_GET['callback'])) {
	// $showForm = !$TotalFlex->handleCallback();
// }

// if ($showForm) {
// 	echo $TotalFlex->generate('business_entity_alias', TotalFlex::CtxCreate, 'TotalFlex\QueryFormatter\Html');
// }

// $TotalFlex->generate ( 'business_entity_alias' , TotalFlex::CtxCreate , new \TotalFlex\QueryFormatter\Html ( ) )

?>

<h3>Saida</h3>

<div class="row">
	
	<div class="col-md-6">
		
		<?=\TotalFlex\View\Formatter\Html::generate ( $TotalFlex->getView ( 'business_entity_alias' ) , TotalFlex::CtxCreate )?>

	</div>

</div>

	

</body>
</html>