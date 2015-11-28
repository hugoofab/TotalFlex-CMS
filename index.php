<?php

require 'vendor/autoload.php';

use TotalFlex\TotalFlex;
use TotalFlex\Field;
use TotalFlex\Rule\Required;
use TotalFlex\Rule\Length;

/************************************************************
 * Creating fake business database
 * ---------------------------------------------------------
 * It's not supposed to be anywhere in the code. Creating
 * here to show the infraestructure expected from the 
 * target application.
 *************************************************************/
$conn = new PDO('sqlite:business.db3');
$conn->query("CREATE TABLE IF NOT EXIST business_entity (id_be INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT)");
$conn = null;

/************************************************************
 * Bootstraping TotalFlex
 * ---------------------------------------------------------
 * This code is needed every initialization of the 
 * application. There is, in te future, ideas to make this
 * part of one simple SQLite database that will only need
 * one line to bootstrap it all.
 *************************************************************/

// Initializing total flex with callback url and the target database connection info
$totalFlex = new TotalFlex('index.php?callback=1', 'sqlite:business.db3');

// Registering table `business_entity` with its fields
$totalFlex->registerTable('business_entity')
	// FIELD id_be
	->addField('id_be')
		->setLabel('Identifier')
		->setPrimaryKey(true)
		->setContexts(TotalFlex::CtxRead)
		->then()
	// FIELD name
	->addField('name')
		->setLabel('Name')
		->setContexts(TotalFlex::CtxCreate|TotalFlex::CtxRead|TotalFlex::CtxUpdate)
		->addRule(new Required())
		->addRule(new Length(10, 20));


/************************************************************
 * TotalFlex Use Case
 * ---------------------------------------------------------
 * This is the real code to generate some form with TotalFlex
 * AND handle the return. 
 *************************************************************/
if (!isset($_GET['callback'])) {
	echo $totalFlex->generate('business_entity', TotalFlex::CtxCreate, 'TotalFlex\QueryFormatter\Html');
} else {
	$totalFlex->handleCallback();
}