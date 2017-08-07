<?php

namespace muv\KundenKonto\Wordpress;


defined( 'ABSPATH' ) OR exit;


class Deactivate {

	
	public static function init() {
		
		wp_clear_scheduled_hook( 'muv-kk-cron-delete-accounts' );

		
			}

}
