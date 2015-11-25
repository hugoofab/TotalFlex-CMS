<?php

require 'vendor/autoload.php';
require 'src/TotalFlex.php';
use TotalFlex\TotalFlex;

$crudManager = new TotalFlex('tf.db3', 'tf_', [
	'dsn' => 'sqlite:business.db3'
]);