<?php

namespace muv\KundenKonto\Frontend;

use muv\KundenKonto\Classes\Auth;



defined( 'ABSPATH' ) OR exit;


class Main {

	
	public static function init() {

		
		Shortcodes::init();
		Login::init();
		Logout::init();

		
		Auth::getLoggedInKunde( false );


		
		add_action( 'wp_enqueue_scripts', array( self::class, 'enqueueScripts' ) );
	}

	public static function enqueueScripts() {
		wp_enqueue_style( 'muv_css', MUV_KK_URL . '/assets/css/frontend-common.css' );
		wp_enqueue_style( 'muv_kk_css', MUV_KK_URL . '/assets/css/frontend.css' );
	}

}
