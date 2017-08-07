<?php

namespace muv\KundenKonto\Classes;


defined( 'ABSPATH' ) OR exit;


class DBTables {

	
	public static function getTables() {
		global $wpdb;

		
		$tables['intversion'] = $wpdb->prefix . 'muv_sh_intversion';

		
		$tables['kunden'] = $wpdb->prefix . 'muv_kk_kunden';

		
		$tables['kundendaten'] = $wpdb->prefix . 'muv_kk_kundendaten';

		
		$tables['kundendaten_ext'] = $wpdb->prefix . 'muv_kk_kundendaten_ext';

		return $tables;
	}

}
