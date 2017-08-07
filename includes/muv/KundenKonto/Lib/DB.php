<?php

namespace muv\KundenKonto\Lib;


defined( 'ABSPATH' ) OR exit;


class DB {

	private $dbh;
	private $stmt;

	
	protected $tables = [];

	
	public function __construct() {
				$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
				$options = array(
			\PDO::ATTR_PERSISTENT => true,
			\PDO::ATTR_ERRMODE    => \PDO::ERRMODE_EXCEPTION
		);
				$this->dbh = new \PDO( $dsn, DB_USER, DB_PASSWORD, $options );

		global $wpdb;

		
		$this->tables['intversion'] = $wpdb->prefix . 'muv_sh_intversion';
	}

	
	public function tbl( $id ) {
		
		if ( ! empty( $this->tables[ $id ] ) ) {
			return $this->tables[ $id ];
		} else {
			die( "muv-DB - internal error!" );
		}
	}

	
	private function prepare( $query, $option = array() ) {
		$this->stmt = $this->dbh->prepare( $query, $option );
	}

	
	private function bind( $param, $value, $type = null ) {
		if ( is_null( $type ) ) {
			switch ( true ) {
				case is_int( $value ):
					$type = \PDO::PARAM_INT;
					break;
				case is_bool( $value ):
					$type = \PDO::PARAM_BOOL;
					break;
				case is_null( $value ):
					$type = \PDO::PARAM_NULL;
					break;
				default:
					$type = \PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue( $param, $value, $type );
	}

	
	private function execute() {
		try {
			return $this->stmt->execute();
		} catch ( \PDOException $exc ) {
			return false;
		}
	}

	
	public function exec( $sql, $params = [] ) {
		$this->prepare( $sql );
		if ( is_array( $params ) ) {
			foreach ( $params as $param => $value ) {
				$this->bind( $param, $value );
			}
		} else {
			$this->bind( 1, $params );
		}
		$this->execute();
	}

	
	public function getAll( $sql, $params = [] ) {
		$this->exec( $sql, $params );

		return $this->stmt->fetchAll( \PDO::FETCH_ASSOC );
	}

	
	public function getRow( $sql, $params = [] ) {
		$this->exec( $sql, $params );

		return $this->stmt->fetch( \PDO::FETCH_ASSOC );
	}

	
	public function getOne( $sql, $params = [] ) {
		$this->exec( $sql, $params );

		return $this->stmt->fetchColumn( 0 ); 	}

	
	public function rowCount() {
		return $this->stmt->rowCount();
	}

	
	public function lastInsertId() {
		return $this->dbh->lastInsertId();
	}

	
	public function tableExists( $tableName ) {
		$anz = $this->getOne( "SHOW TABLES LIKE '" . $tableName . "'" );

		return ( ! empty( $anz ) );
	}

}
