<?php

namespace TotalFlex\Database;

class QueryBuilder {
	/**
	 * @var \PDO $_db Database connection
	 */
	private $_db;

	/**
	 * @var Class $_model Model that will be constructed by this query builder
	 */
	private $_model;

	/**
	 * Constructs the QueryBuilder to one Model class
	 *
	 * @param Class $model (Optional) Model's class
	 */
	public function __construct($db, $model = 'TotalFlex\BaseModel') {
		$this->_db = $db;
		$this->_model = $model;
	}

	/**
	 * Returns all registries from a query.
	 *
	 * @param string $query Query
	 * @param array $bindList Bind parameter list
	 */
	public function all($query , $bindList = array ( ) ) {
		$stmt = $this->_db->prepare($query);
		
		// TODO: Surround with try-catch and handle exceptions
		$stmt->execute($bindList);
		
		// Fetch and build results
		return $stmt->fetchAll(\PDO::FETCH_CLASS,  $this->_model);
	}

	public function one( $query , $bindList = array ( ) ) {
		$stmt = $this->_db->prepare($query);
		
		// TODO: Surround with try-catch and handle exceptions
		$stmt->execute($bindList);

		// Fetch and build results
		$stmt->setFetchMode(\PDO::FETCH_CLASS,  $this->_model); 
		return $stmt->fetch();
	}

	public function oneValue( $query , $bindList = array ( ) ) {
		$stmt = $this->_db->prepare($query);
		
		// TODO: Surround with try-catch and handle exceptions
		$stmt->execute($bindList);

		// Fetch and build results
		$stmt->setFetchMode(\PDO::FETCH_NUM); 
		return $stmt->fetch()[0];

	}

	public function allValues( $query , $bindList = array ( ) ) {
		$stmt = $this->_db->prepare($query);
		
		// TODO: Surround with try-catch and handle exceptions
		$stmt->execute($bindList);

		// Fetch and build results
		$pdoResults = $stmt->fetchAll(\PDO::FETCH_NUM);
		$results = [];

		foreach ($pdoResults as $pdoResult) {
			$results[] = $pdoResult[0];
		}

		return $results;
	}
}