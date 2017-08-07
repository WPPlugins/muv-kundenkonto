<?php

namespace muv\KundenKonto\Classes;


defined( 'ABSPATH' ) OR exit;


class DB extends \muv\KundenKonto\Lib\DB {

	
	public function __construct() {
		parent::__construct();

		
		$this->tables = DBTables::getTables();
	}

}
