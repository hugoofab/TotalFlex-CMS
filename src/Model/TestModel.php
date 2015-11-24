<?php

namespace TotalFlex\Model;
use TotalFlex\Database\BaseModel;

class TestModel extends BaseModel {
	private $id;
	private $name;

	public static function getDB() {
		return new \PDO("sqlite:test.db3");
	}
}