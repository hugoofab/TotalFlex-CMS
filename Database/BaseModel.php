<?php

namespace TotalFlex\Database;

abstract class BaseModel {
	/**
	 * @var \PDO $_db Database connection
	 */
	private $_db;

	/**
	 * Constructs the query builder to the object 
	 *
	 * @return TotalFlex\Database\QueryBuilder Query builder to this operation
	 */
	public static function find() {
		if (method_exists(get_called_class(), 'getDB')) {
			$calledClass = get_called_class();
			$database = call_user_func([$calledClass, 'getDB']);
			return new QueryBuilder($database, $calledClass);
		} else {
			throw new \RuntimeException("Expected " . get_called_class() . " to have getDB method.");
		}
	}
}