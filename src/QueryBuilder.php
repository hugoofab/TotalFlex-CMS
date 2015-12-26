<?php

namespace TotalFlex ;

class QueryBuilder {

	/**
	 * @var array used to make from statement
	 */
	protected $_queryFrom = [];
	protected $_queryJoin = [];

	public static function getInstance ( ) {
		return new QueryBuilder();
	}

	public function from ( $queryFrom ) {
		array_push ( $this->_queryFrom , $queryFrom ) ;
        return $this ;
	}

    public function join ( $queryJoin ) {
        $this->queryJoin[] = " JOIN "       . $queryJoin . " \n" ;
        return $this ;
    }

    public function innerJoin ( $queryJoin ) {
        $this->queryJoin[] = " INNER JOIN " . $queryJoin . " \n" ;
        return $this ;
    }

    public function leftJoin ( $queryJoin ) {
        $this->queryJoin[] = " LEFT JOIN "  . $queryJoin . " \n" ;
        return $this ;
    }

    public function rightJoin ( $queryJoin ) {
        $this->queryJoin[] = " RIGHT JOIN " . $queryJoin . " \n" ;
        return $this ;
    }	

    /**
     * @todo #25 precisa aplicar regras de segurança etc... 
     * [getCreateQuery description]
     * @param  [type] $fieldList [description]
     * @return [type]            [description]
     */
    public function getCreateQuery ( $fieldList ) {

    	$keyList = array_keys ( $fieldList );

    	$output = "INSERT INTO " . implode ( ', ' , $this->_queryFrom ) . "( " . implode ( ', ' , $keyList ) . " ) VALUES ".
    	"( '" . implode ( "','" , $fieldList ) . "' )";

    	return $output;
    }


}