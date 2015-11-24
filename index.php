<?php

require 'vendor/autoload.php';
require 'src/TotalFlex.php';
use TotalFlex\TotalFlex;

header('Content-Type: text/plain');

$crudManager = new TotalFlex('tf.db3', [
	'dsn' => 'sqlite:business.db3'
]);