<?php

require 'vendor/autoload.php';
use TotalFlex\Model\TestModel;

header('Content-Type: text/plain');

echo "find()->all(query):\n";
print_r(TestModel::find()->all("SELECT * FROM testtable"));
echo "\n";

echo "find()->one(query):\n";
print_r(TestModel::find()->one("SELECT * FROM testtable"));
echo "\n";

echo "find()->allValues(query):\n";
print_r(TestModel::find()->allValues("SELECT name, id FROM testtable"));
echo "\n";

echo "find()->oneValue(query):\n";
print_r(TestModel::find()->oneValue("SELECT name, id FROM testtable"));
echo "\n\n";

echo "find()->all(query, bind):\n";
print_r(TestModel::find()->all("SELECT * FROM testtable WHERE id>:min", ['min' => 1]));
echo "\n";

echo "find()->one(query, bind):\n";
print_r(TestModel::find()->one("SELECT * FROM testtable WHERE id=:id", ['id' => 2]));
echo "\n";

echo "find()->allValues(query, bind):\n";
print_r(TestModel::find()->allValues("SELECT name, id FROM testtable WHERE id=:id", ['id' => 1]));
echo "\n";

echo "find()->oneValue(query, bind):\n";
print_r(TestModel::find()->oneValue("SELECT name, id FROM testtable WHERE id=:id", ['id' => 2]));
echo "\n\n";