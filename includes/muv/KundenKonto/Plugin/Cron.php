<?php

namespace muv\KundenKonto\Plugin;

use muv\KundenKonto\Classes\DB;


defined( 'ABSPATH' ) OR exit;


class Cron {

	
	public static function init() {
		add_action( 'muv-kk-cron-delete-accounts', array( self::class, 'deleteAccounts' ) );
	}

	
	public static function deleteAccounts() {
		
		$optionen = get_option( 'muv-kk-zugang-loeschen', array() );
		$checked  = (bool) $optionen['check'];
		$tage     = abs( (int) $optionen['tage'] );

		
		if ( ! $checked ) {
			return;
		}

				$erstelltBis = date( 'Y-m-d H:i:s', strtotime( '-' . $tage . ' days', time() ) );

		
		$db  = new DB();
		$sql = "DELETE FROM " . $db->tbl( 'kunden' ) . " WHERE erstellt_am_utc < ? AND email_verifiziert_am_utc IS NULL";
		$db->exec( $sql, $erstelltBis );
	}
}
