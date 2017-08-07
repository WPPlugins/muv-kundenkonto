<?php

namespace muv\KundenKonto\Wordpress;

use muv\KundenKonto\Classes\DBTables;



defined( 'ABSPATH' ) OR exit;


class Uninstall {

	
	public static function init() {
		
		self::dropTables();
	}

	
	private static function dropTables() {

		
		global $wpdb;

		
		$tables = DBTables::getTables();

		
		foreach ( $tables as $table ) {
			if ( $table !== $tables['intversion'] ) {
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $table );
			}
		}

		
		$tblGefunden = $wpdb->get_row( "SHOW TABLES LIKE '" . $tables['intversion'] . "'" );
		if ( ! empty( $tblGefunden ) ) {
			
			$sql = $wpdb->prepare( "DELETE FROM " . $tables['intversion'] . " WHERE `identifier` = %s", MUV_KK_UPATE_IDENTIFIER );
			$wpdb->query( $sql );

			

			$rest = $wpdb->get_var( "SELECT COUNT(*) FROM " . $tables['intversion'] );
			if ( empty( $rest ) ) {
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $tables['intversion'] );
			}
		}
	}

}
