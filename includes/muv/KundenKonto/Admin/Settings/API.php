<?php

namespace muv\KundenKonto\Admin\Settings;

use muv\KundenKonto\Classes\Flash;


defined( 'ABSPATH' ) OR exit;


class API {

	
	public static function init() {
		
		add_action( 'admin_init', array( self::class, 'addSettings' ) );
		
		add_action( 'admin_menu', array( self::class, 'addAdminMenuItem' ) );

		
		if ( Flash::getValue( 'muv-kk-api-key-neu', 0 ) === 1 ) {
			add_action( 'admin_notices', array( self::class, 'apiKeyNeuBerechnet' ) );
		}
	}

	
	public static function addAdminMenuItem() {
		
		add_submenu_page( null, '', '', 'manage_options', 'muv-kk-apikey-neu', array(
			self::class,
			'erzeugeNeuenAPIKey'
		) );
	}

	
	public static function erzeugeNeuenAPIKey() {
		
		check_admin_referer( 'muv-kk-api-key-neu' );

		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Sie haben nicht das Recht, diese Seite zu sehen!', 'muv-minishop' ) );
		}

		
		update_option( 'muv-kk-api-key', bin2hex( openssl_random_pseudo_bytes( 50 ) ), 'no' );
		
		Flash::setValue( 'muv-kk-api-key-neu', 1 );
		
		wp_redirect( 'admin.php?page=muv-kk-einstellungen&tab=api' );
	}

	
	public static function apiKeyNeuBerechnet() {
		echo '<div class="notice notice-success">';
		echo '<p>';
		echo __( '<b>Der API-Key wurde neu berechnet.</b>' .
		         ' Bitte beachten Sie, dass ein Zugriff mit dem alten Key ab sofort nicht mehr möglich ist.', 'muv-kundenkonto' );
		echo '</p>';
		echo '</div>';
	}

	
	public static function handleSettings() {
		echo '<form method="post" action="options.php">';

		
		settings_fields( 'muv-kk-settings-api' );
		
		do_settings_sections( 'muv-kk-settings-api' );
		
		
		echo '</form>';
	}

	
	public static function addSettings() {
		
		add_settings_section( 'muv-kk-apikey', __( 'API-Key', 'muv-kundenkonto' ), array(
			self::class,
			'sectionApiKeyBeschreibung'
		), 'muv-kk-settings-api' );
	}

	
	public static function sectionApiKeyBeschreibung() {
		echo __( 'Zum Zugriff auf die API wird folgender Key benötigt:', 'muv-kundenkonto' );
		echo '<br><br>';
		echo '<input readonly type="text" class="gross" value="' . esc_html( get_option( 'muv-kk-api-key', '' ) ) . '">';
		
		echo '<br><br><a href="' . wp_nonce_url( 'admin.php?page=muv-kk-apikey-neu', 'muv-kk-api-key-neu' ) .
		     '" class="button"><i class="fa fa-refresh"> </i> ' .
		     __( 'API-Key neu vergeben', 'muv-kundenkonto' ) . '</a><br><br>';
	}

}
