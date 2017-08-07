<?php

namespace muv\KundenKonto\Admin\Settings;

use muv\KundenKonto\Classes\Tools;
use muv\KundenKonto\Classes\DefaultSettings;


defined( 'ABSPATH' ) OR exit;


class Allgemein {

	
	public static function init() {
				add_action( 'admin_init', array( self::class, 'addSettings' ) );
	}

	
	public static function handleSettings() {
		echo '<form method="post" action="options.php" enctype="multipart/form-data">';

				settings_fields( 'muv-kk-settings-allgemein' );
				do_settings_sections( 'muv-kk-settings-allgemein' );
				submit_button();

		echo '</form>';
	}

	
	public static function addSettings() {
		
		add_settings_section( 'muv-kk-zugang', __( 'Kunden-Konten', 'muv-kundenkonto' ),
			array( self::class, 'sectionZugangBeschreibung' ), 'muv-kk-settings-allgemein' );

		add_settings_field( 'muv-kk-zugang-loeschen', __( 'Konten löschen', 'muv-kundenkonto' ),
			array( self::class, 'zugangLoeschenHtml' ), 'muv-kk-settings-allgemein', 'muv-kk-zugang' );
		register_setting( 'muv-kk-settings-allgemein', 'muv-kk-zugang-loeschen', array(
			self::class,
			'zugangLoeschenValidate'
		) );

		
		add_settings_section( 'muv-kk-anmeldung', __( 'Kunden-Login', 'muv-kundenkonto' ),
			array( self::class, 'sectionLoginBeschreibung' ), 'muv-kk-settings-allgemein' );

		add_settings_field( 'muv-kk-erlaube-pseudo-login', __( 'Pseudo-Login', 'muv-kundenkonto' ),
			array( self::class, 'pseudoLoginHtml' ), 'muv-kk-settings-allgemein', 'muv-kk-anmeldung' );
		register_setting( 'muv-kk-settings-allgemein', 'muv-kk-erlaube-pseudo-login', array(
			self::class,
			'pseudoLoginValidate'
		) );

		add_settings_field( 'muv-kk-login-domain'
			, __( 'Login-Domain', 'muv-kundenkonto' )
			, array( self::class, 'anmeldungDomainHtml' )
			, 'muv-kk-settings-allgemein'
			, 'muv-kk-anmeldung' );
		register_setting( 'muv-kk-settings-allgemein', 'muv-kk-login-domain', array(
			self::class,
			'anmeldungDomainValidate'
		) );

		add_settings_field( 'muv-kk-logout', __( 'Automatische Abmeldung', 'muv-kundenkonto' ),
			array( self::class, 'abmeldungHtml' ), 'muv-kk-settings-allgemein', 'muv-kk-anmeldung' );
		register_setting( 'muv-kk-settings-allgemein', 'muv-kk-logout', array( self::class, 'abmeldungValidate' ) );
	}

	
	public static function sectionZugangBeschreibung() {
		_e( 'Die Einstellungen der Kunden-Konten (Zugänge).', 'muv-kundenkonto' );
	}

	
	public static function zugangLoeschenValidate( $werte ) {
		
		$ok = [];

		
		$check = isset( $werte['check'] );

		
		$tage = filter_var( $werte['tage'], FILTER_SANITIZE_NUMBER_INT );
		if ( empty( $tage ) ) {
			$tage = DefaultSettings::ALLGEMEIN_ZUGANG_LOESCHEN['tage'];
		}
		$tage = abs( $tage ); 
		$ok['check'] = $check;
		$ok['tage']  = $tage;

		return $ok;
	}

	
	public static function zugangLoeschenHtml() {
		$optionen = get_option( 'muv-kk-zugang-loeschen', array() );
		if ( empty( $optionen ) ) {
			$optionen = DefaultSettings::ALLGEMEIN_ZUGANG_LOESCHEN;
		}
		$checked = checked( true, $optionen['check'], false );
		$tage    = abs( (int) $optionen['tage'] );

		echo '<input name="muv-kk-zugang-loeschen[check]" type="checkbox" value="1" ' . $checked . '/>';
		printf( __( 'Nicht aktivierte Kunden-Konten löschen, die vor mehr als %s Tagen angelegt wurden.', 'muv-kundenkonto' ),
			'<input name="muv-kk-zugang-loeschen[tage]" type="number" min="1" step="1" value="' . $tage .
			'" class="small-text" />' );
	}

	
	public static function sectionLoginBeschreibung() {
		_e( 'Die Einstellungen der angemeldeten Kunden.', 'muv-kundenkonto' );
	}


	
	public static function pseudoLoginValidate( $wert ) {
		

		
		$check = isset( $wert );

		return $check;
	}

	
	public static function pseudoLoginHtml() {
		$optionen = get_option( 'muv-kk-erlaube-pseudo-login', DefaultSettings::ERLAUBE_PSEUDO_LOGIN );

		$checked = checked( true, $optionen, false );

		Tools::tooltip( __( 'Pseudo-Login dienen z.B. dazu, den Kunden zu begrüßen und ihm erste Informationen anzuzeigen.' .
		                    '<br>' .
		                    'Ein Pseudo-Login liegt dann vor, wenn der Kunde sich zwar in einer vorherigen Session angemeldet hat, ' .
		                    'aber noch nicht in der Aktuellen.', 'muv-kundenkonto' ) );
		echo '<label>';
		echo '<input name="muv-kk-erlaube-pseudo-login" type="checkbox" value="1" ' . $checked . '/>';
		echo __( 'Pseudo-Login erlauben', 'muv-kundenkonto' );
		echo '</label>';
	}


	
	public static function abmeldungValidate( $werte ) {
		
		$ok = [];

				$idle = filter_var( $werte['idle'], FILTER_SANITIZE_NUMBER_INT );
		if ( empty( $idle ) ) {
			$idle = DefaultSettings::ALLGEMEIN_ABMELDUNG['idle'];
		}
		$idle = abs( $idle ); 				$gesamt = filter_var( $werte['gesamt'], FILTER_SANITIZE_NUMBER_INT );
		if ( empty( $gesamt ) ) {
			$gesamt = DefaultSettings::ALLGEMEIN_ABMELDUNG['gesamt'];
		}
		$gesamt = abs( $gesamt ); 
		$ok['idle']   = $idle;
		$ok['gesamt'] = $gesamt;

		return $ok;
	}

	
	public static function abmeldungHtml() {
		$optionen = get_option( 'muv-kk-logout', array() );
		if ( empty( $optionen ) ) {
			$optionen = DefaultSettings::ALLGEMEIN_ABMELDUNG;
		}
		$idle   = abs( (int) $optionen['idle'] );
		$gesamt = abs( (int) $optionen['gesamt'] );

		printf( __( 'Kunden abmelden, wenn diese länger als %s Minuten inaktiv waren.', 'muv-kundenkonto' )
			, '<input name="muv-kk-logout[idle]" type="number" min="1" step="1" value="' . $idle .
			  '" class="small-text" />' );

		echo '<br>';

		printf( __( 'Kunden abmelden, wenn diese länger als %s Stunden angemeldet sind.', 'muv-kundenkonto' )
			, '<input name="muv-kk-logout[gesamt]" type="number" min="1" step="1" value="' . $gesamt .
			  '" class="small-text" />' );
	}

	
	public static function anmeldungDomainValidate( $wert ) {
		$loginDomain = trim( $wert );

				$host = parse_url( get_home_url(), PHP_URL_HOST );

		
		if ( strpos( $host, $loginDomain ) === false ) {
						$loginDomain = DefaultSettings::ALLGEMEIN_ANMELDUNG_DOMAIN();
						add_settings_error( 'muv-kk-login-domain'
				, 'muv-kk-login-domain'
				,
				__( 'Bei der Login-Domain muss es sich um die Domain Ihres Wordpress-Auftritts oder um eine Subdomain davon handeln.',
					'muv-kundenkonto' ) );
		}

		return $loginDomain;
	}

	
	public static function anmeldungDomainHtml() {
		$loginDomain = parse_url( 'http://' . get_option( 'muv-kk-login-domain', '' ), PHP_URL_HOST );
		if ( empty( $loginDomain ) ) {
			$loginDomain = DefaultSettings::ALLGEMEIN_ANMELDUNG_DOMAIN();
		}

		echo '<input name="muv-kk-login-domain" type="text" class="regular-text" maxlength="100" value="' . esc_html( $loginDomain ) . '" /> ';
		Tools::tooltip( __( 'Ändern Sie diesen Wert nur, wenn Sie die Anmeldung eines Kundens über mehrere Subdomains ' .
		                    'hinweg teilen möchten.<br>' .
		                    'Damit ein Kunde, der sich z.B. unter "login.muv.com" anmeldet auch unter "app.muv.com" ' .
		                    'angemeldet erscheint, geben Sie "muv.com" in dieses Feld ein. ', 'muv-kundenkonto' ) );
	}

}
