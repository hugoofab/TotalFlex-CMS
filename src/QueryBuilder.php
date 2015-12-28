<?php

namespace TotalFlex ;

class QueryBuilder {

	/**
	 * @var array used to make from statement
	 */
	protected $_queryFrom  = [];
	protected $_queryJoin  = [];
	protected $_queryWhere = [];

	public static function getInstance ( ) {
		return new QueryBuilder();
	}

	public function from ( $queryFrom ) {
		array_push ( $this->_queryFrom , $queryFrom ) ;
        return $this ;
	}

    public function join ( $queryJoin ) {
        $this->_queryJoin[] = " JOIN "       . $queryJoin . " \n" ;
        return $this ;
    }

    public function innerJoin ( $queryJoin ) {
        $this->_queryJoin[] = " INNER JOIN " . $queryJoin . " \n" ;
        return $this ;
    }

    public function leftJoin ( $queryJoin ) {
        $this->_queryJoin[] = " LEFT JOIN "  . $queryJoin . " \n" ;
        return $this ;
    }

    public function rightJoin ( $queryJoin ) {
        $this->_queryJoin[] = " RIGHT JOIN " . $queryJoin . " \n" ;
        return $this ;
    }

    public function where ( $condition ) {
		array_push ( $this->_queryWhere , $condition ) ;
        return $this;
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

    /**
     * @todo #25 precisa aplicar regras de segurança etc...
     * return query used to get the data we want to update
     * @return string query string
     */
    public function getUpdateGetQuery ( Array $fieldList ) {

    	// pr($fieldList);
    	$fieldSelect = array ( );
    	foreach ( $fieldList as $Field ) {
    		if ( !is_a ( $Field , 'TotalFlex\Field\Field' ) ) continue ;
    		$fieldSelect[] = $Field->getColumn ( );
    	}

		$query = "SELECT \n\t" . implode ( ", \n\t" , $fieldSelect ) . " \n\nFROM " . implode ( ", \n\t" , $this->_queryFrom ) . "\n" . implode ( "\n\t" , $this->_queryJoin ) ;
		if ( count ( $this->_queryWhere ) > 0 )	$query .= "\nWHERE " . implode ( " \nAND " , $this->_queryWhere ) ;

		return $query ;

    }

    /**
     * @todo #25 precisa aplicar regras de segurança etc...
     * return query used to save the data we want to update
     * @return string query string
     */
    public function getUpdateSaveQuery ( Array $fieldList ) {

    	$fieldValuesList = $primaryKeyList = array ( );
    	foreach ( $fieldList as $Field ) {
    		if ( !is_a ( $Field , 'TotalFlex\Field\Field' ) ) continue ;

    		if ( $Field->isPrimaryKey ( ) ) {
    			$primaryKeyList[] = " " . $Field->getColumn() . " = ? ";
    		} else {
	    		$fieldValuesList[] = " " . $Field->getColumn() . " = ? ";
    		}
    	}

    	$query = "UPDATE " . implode ( ", \n\t" , $this->_queryFrom ) . " SET " . implode ( ", \n\t" , $fieldValuesList ) . " WHERE " . implode ( " AND \n\t" , $primaryKeyList );

    	return $query ;

    }

}