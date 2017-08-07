<?php

namespace muv\KundenKonto\Admin;


defined( 'ABSPATH' ) OR exit;


class Main {

	
	public static function init() {
		
		Settings::init();

		
		add_action( 'admin_menu', array( self::class, 'addAdminMenuItem' ) );

		
		add_action( 'admin_print_scripts', array( self::class, 'adminScriptsMuVBackend' ) );
	}

	
	public static function addAdminMenuItem() {
		
		global $menu;
		$menu[1301] = array( '', 'read', "separator-muv", '', 'wp-menu-separator' );
		$menu[1319] = array( '', 'read', "separator-muv", '', 'wp-menu-separator' );

		

		
		add_menu_page( __( 'muv Kundenkonto', 'muv-kundenkonto' ), __( 'muv Kundenkonto', 'muv-kundenkonto' ), 'manage_options', 'muv-kundenkonto', array(
			Kunden::class,
			'handleKunden'
		), MUV_KK_URL . '/assets/img/logo.png', 1304 );

		

		
		add_submenu_page( 'muv-kundenkonto', __( 'Kunden', 'muv-kundenkonto' ), __( 'Kunden', 'muv-kundenkonto' ), 'manage_options', 'muv-kk-kunden', array(
			Kunden::class,
			'handleKunden'
		) );

		
		add_submenu_page( 'muv-kundenkonto', __( 'Einstellungen', 'muv-kundenkonto' ), __( 'Einstellungen', 'muv-kundenkonto' ), 'manage_options', 'muv-kk-einstellungen', array(
			Settings::class,
			'handleSettings'
		) );

		
		remove_submenu_page( 'muv-kundenkonto', 'muv-kundenkonto' );
	}


	
	public static function adminScriptsMuVBackend() {
		$screen = get_current_screen();

		
		if ( strpos( $screen->id, '_page_muv-' ) !== false ) {
			
			wp_enqueue_style( 'muv-fa', MUV_KK_URL . '/vendor/public/font-awesome/css/font-awesome.min.css' );
			
			wp_enqueue_style( 'muv-tooltip', MUV_KK_URL . '/vendor/public/tipso/src/tipso.min.css' );
			
			wp_enqueue_script( 'muv-tooltip', MUV_KK_URL . '/vendor/public/tipso/src/tipso.min.js', array( 'jquery' ), false, true );
			
			wp_enqueue_script( 'muv-datatables', MUV_KK_URL . '/vendor/public/datatables.net/js/jquery.dataTables.min.js', array( 'jquery' ), false, true );
			wp_enqueue_style( 'muv-datatables', MUV_KK_URL . '/vendor/public/datatables.net-dt/css/jquery.dataTables.min.css' );

			
			wp_enqueue_style( 'wp-color-picker' );

			
			wp_enqueue_script( 'muv-admin', MUV_KK_URL . '/assets/js/admin-common.js', array(
				'jquery',
				'muv-tooltip',
				'wp-color-picker',
				'muv-datatables'
			), false, true );

			
			wp_enqueue_style( 'muv-admin', MUV_KK_URL . '/assets/css/admin-common.css' );
		}

		
		if ( strpos( $screen->id, '_page_muv-kk' ) !== false ) {
			
			wp_enqueue_style( 'muv-kk-admin', MUV_KK_URL . '/assets/css/admin.css' );
			
			wp_enqueue_script( 'muv-kk-admin', MUV_KK_URL . '/assets/js/admin.js', array( 'jquery' ), false, true );
		}

	}

}
