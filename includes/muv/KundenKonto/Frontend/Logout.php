<?php

namespace muv\KundenKonto\Frontend;

use muv\KundenKonto\Classes\Auth;


defined( 'ABSPATH' ) OR exit;


class Logout {

	
	public static function init() {
		add_action( 'init', array( self::class, 'addEndpoints' ) );
	}

	
	public static function addEndPoints() {
				add_filter( 'template_redirect', array( self::class, 'endpointDoLogout' ) );
	}

	
	public static function endpointDoLogout() {

		global $wp;

				if ( $wp->request !== 'muv-kundenkonto/logout' ) {
			return;
		}

		
		$kunde = Auth::getLoggedInKunde(true);
		if ( ! empty( $kunde ) ) {
			$kunde->logout();
		}

		
		$goto = filter_input( INPUT_GET, 'muv-kk-seite', FILTER_SANITIZE_URL );
		if ( empty( $goto ) ) {
			$goto = '/';
		}

				wp_redirect( $goto );
		exit;
	}
}