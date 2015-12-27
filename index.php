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
// $pdo = new PDO('sqlite:business.db3');
// $conn = new PDO('mysqli:business.db3');
// $conn->query("CREATE TABLE IF NOT EXISTS business_entity (id_be INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT)");
// $pdo = new FluentPDOPDO("mysql:dbname=fazerbrasil", "root", "");
// $pdo->query("INSERT INTO business_entity( name ) VALUES ( 'asdfasdf' )");
// $pdo = new PDO("mysql:host=localhost;dbname=fazerbrasil", "root", "");
// pr($pdo);
// $conn = null;

// macbook
// $pdo = new PDO("mysql:host=10.0.1.8;dbname=fazerbrasil", "root", "");
$pdo = new PDO("mysql:dbname=fazerbrasil", "root", "");

// imac
// $pdo = new PDO("mysql:dbname=test", "root", "");


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

// $result = $pdo->query("SELECT * FROM business_entity");

$TotalFlex = new TotalFlex ( $pdo );

// DEFAULT TEMPLATE #################################################################
	\TotalFlex\View\Formatter\Html::$defaultTemplateCollection['start']         = "<div class=\"col-md-6 form-group\">\n";
	\TotalFlex\View\Formatter\Html::$defaultTemplateCollection['end']           = "</div>\n";
	\TotalFlex\View\Formatter\Html::$defaultTemplateCollection['input']['text'] = "\t<input class=\"form-control\" type=\"__type__\" name=\"__name__\" id=\"__id__\" value=\"__value__\"/><br>\n\n";
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
$TotalFlex->registerView('business_entity')

	// set default contexts to be applyed to all fields from now on

	->setContexts(TotalFlex::CtxRead)
	->addField( Field\Text::getInstance ( 'id_news_label' , 'ID' ) )->setPrimaryKey()

	->setContexts(TotalFlex::CtxUpdate|TotalFlex::CtxCreate|TotalFlex::CtxRead)
	->addField( Field\Text::getInstance ( 'label' , 'Label' ) )

	->addButton ( new Button ( "Salvar" , array ( 'class' => "btn btn-primary" , "type" => "submit" ) ) )

	->where ( "id_news_label = 1" )

	->setTable ( "news_label" )

	// ->WHERE ( 'fieldid = X' )
	// para criar o contexto de editar, precisa encontrar a tupla que desejamos.

	// deve gerar uma excessão caso encontre mais que uma tupla?
	// possivelmente geraria uma excessão mas permitiria com um metodo a edição multipla,
	// 		assim o padrão é não aceitar, mas se o usuário declarar explicitamente que deseja alterar multiplas tuplas, que assim seja.

	// caso não encontre nenhuma tupla, podemos gerar uma exce

;

// $TotalFlex->processPost ( "business_entity" , TotalFlex::CtxCreate ) ;
$TotalFlex->processPost ( "business_entity" , TotalFlex::CtxUpdate ) ;

echo \TotalFlex\Feedback::dumpMessages();

// prs($pdo->query("SELECT * FROM news_label")->fetchAll());
// pr($consulta->fetchAll());


// $TotalFlex->processPost ( "business_entity" , TotalFlex::CtxUpdate ) ;
// $TotalFlex->processPost ( "business_entity" , TotalFlex::CtxRead ) ;

// $TotalFlex->processPost ( "business_entity" , TotalFlex::CtxUpdate|TotalFlex::CtxRead|TotalFlex::CtxCreate ) ;

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

		<!-- experimental -->
		<? // =\TotalFlex\View\Formatter\Html::generate ( $TotalFlex->getView ( 'business_entity' ) , TotalFlex::CtxCreate ) ?>
		<? // =\TotalFlex\View\Formatter\Html::generate ( $TotalFlex->getView ( 'business_entity' ) , TotalFlex::CtxUpdate ) ?>

		<!-- o terceiro parametro pode ser uma string com o nome da classe TotalFlex\View\Formatter ou pode ser uma instancia do próprio formatter -->
		<!-- no caso de ser uma instancia, você pode instancia-lo e fazer as devidas configurações no formatter antes de injeta-lo no metodo TotalFlex->generate() -->
		<!-- caso não seja necessário configurar nada no View\Formatter, pode passar só o nome do mesmo como string  -->
		<?=$TotalFlex->generate ( 'business_entity' , TotalFlex::CtxUpdate , 'html' )?>

	</div>

</div>



</body>
</html>