<?php

namespace muv\KundenKonto\Frontend;

use muv\KundenKonto\Classes\Auth;
use muv\KundenKonto\Classes\Flash;


defined( 'ABSPATH' ) OR exit;


class Login {

	
	public static function init() {
		add_action( 'init', array( self::class, 'addEndpoints' ) );
	}

	
	public static function addEndPoints() {
				add_filter( 'template_redirect', array( self::class, 'endpointDoLogin' ) );
	}

	
	public static function endpointDoLogin() {

		global $wp;

				if ( $wp->request !== 'muv-kundenkonto/login' ) {
			return;
		}

		
		$login = filter_input( INPUT_POST, 'muv-kk-login', FILTER_SANITIZE_STRING );
		$pwd   = filter_input( INPUT_POST, 'muv-kk-passwort', FILTER_SANITIZE_STRING );
		$res   = Auth::checkLogin( $login, $pwd );
		if ( $res !== true ) {
			switch ( $res ) {
				case - 1:
					Flash::addMessage( __( 'Bitte geben Sie Ihre E-Mail Adresse ein.', 'muv-kundenkonto' ), 'danger' );
					Flash::setValue( 'muv-kk.auth.do-login.login.error', 'has-error' );
					break;
				case - 2:
					Flash::addMessage( __( 'Bitte geben Sie ein Passwort ein.', 'muv-kundenkonto' ), 'danger' );
					Flash::setValue( 'muv-kk.auth.do-login.passwort.error', 'has-error' );
					break;
				case - 3:
					Flash::addMessage( __( 'Benutzername / Passwort falsch.', 'muv-kundenkonto' ), 'danger' );
					break;
				case - 4:
					Flash::addMessage( __( 'Der von Ihnen verwendete Zugang wurde bisher noch nicht aktiviert.' .
					                       '<br>Wir haben Ihnen deshalb die Bestätigungs-Mail erneut zugesendet.', 'muv-kundenkonto' ), 'info' );
					break;
			}
		}

		
		$goto = filter_input( INPUT_POST, 'muv-kk-seite', FILTER_SANITIZE_URL );
		if ( empty( $goto ) ) {
			$goto = '/';
		}

				wp_redirect( $goto );
		exit;
	}

}
