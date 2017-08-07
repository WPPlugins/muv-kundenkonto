<?php


namespace muv\KundenKonto\Admin\Settings;

use muv\KundenKonto\Classes\Tools;
use \muv\KundenKonto\Classes\DefaultSettings;


defined( 'ABSPATH' ) OR exit;


class EMail {

	
	public static function init() {
		
		add_action( 'admin_init', array( self::class, 'addSettingsAllgemein' ) );
	}

	
	public static function handleSettings() {
		

		
		echo '<form method="post" action="options.php" enctype="multipart/form-data">';

		
		settings_fields( 'muv-kk-settings-email' );

		
		do_settings_sections( 'muv-kk-settings-email' );

		submit_button();
	}

	
	public static function addSettingsAllgemein() {
		
		add_settings_section( 'muv-kk-email-absender', __( 'E-Mail Absender', 'muv-kundenkonto' ),
			array( self::class, 'sectionAbsenderBeschreibung' ), 'muv-kk-settings-email' );

		
		add_settings_field( 'muv-kk-email-von-name'
			, __( '"Von" - Name', 'muv-kundenkonto' )
			, array( self::class, 'vonNameHtml' )
			, 'muv-kk-settings-email'
			, 'muv-kk-email-absender' );
		register_setting( 'muv-kk-settings-email', 'muv-kk-email-von-name', array( self::class, 'vonNameValidate' ) );

		
		add_settings_field( 'muv-kk-email-von-mail'
			, __( '"Von" - E-Mail-Adresse', 'muv-kundenkonto' )
			, array( self::class, 'vonEmailHtml' )
			, 'muv-kk-settings-email'
			, 'muv-kk-email-absender' );
		register_setting( 'muv-kk-settings-email', 'muv-kk-email-von-mail', array( self::class, 'vonEmailValidate' ) );

		
		add_settings_section( 'muv-kk-email-vorlage', __( 'E-Mail Vorlage', 'muv-kundenkonto' ),
			array( self::class, 'sectionVorlageBeschreibung' ), 'muv-kk-settings-email' );

		
		add_settings_field( 'muv-kk-email-vorlage-logo'
			, __( 'Bild für Kopfzeile (Header)', 'muv-kundenkonto' )
			, array( self::class, 'vorlageLogoHtml' )
			, 'muv-kk-settings-email'
			, 'muv-kk-email-vorlage' );
		register_setting( 'muv-kk-settings-email', 'muv-kk-email-vorlage-logo', array(
			self::class,
			'vorlageLogoValidate'
		) );

		
		add_settings_field( 'muv-kk-email-vorlage-footer'
			, __( 'Fußzeile (Footer)', 'muv-kundenkonto' )
			, array( self::class, 'vorlageFooterHtml' )
			, 'muv-kk-settings-email'
			, 'muv-kk-email-vorlage' );
		register_setting( 'muv-kk-settings-email', 'muv-kk-email-vorlage-footer', array(
			self::class,
			'vorlageFooterValidate'
		) );

		
		add_settings_field( 'muv-kk-email-vorlage-color'
			, __( 'Grundfarbe', 'muv-kundenkonto' )
			, array( self::class, 'vorlageColorHtml' )
			, 'muv-kk-settings-email'
			, 'muv-kk-email-vorlage' );
		register_setting( 'muv-kk-settings-email', 'muv-kk-email-vorlage-color', array(
			self::class,
			'vorlageColorValidate'
		) );

		
		add_settings_field( 'muv-kk-email-vorlage-color-text'
			, __( 'Textfarbe', 'muv-kundenkonto' )
			, array( self::class, 'vorlageColorTextHtml' )
			, 'muv-kk-settings-email'
			, 'muv-kk-email-vorlage' );
		register_setting( 'muv-kk-settings-email', 'muv-kk-email-vorlage-color-text',
			array( self::class, 'vorlageColorTextValidate' ) );

		
		add_settings_field( 'muv-kk-email-vorlage-bgcolor-body'
			, __( 'Hintergrundfarbe Rand', 'muv-kundenkonto' )
			, array( self::class, 'vorlageBgColorBodyHtml' )
			, 'muv-kk-settings-email'
			, 'muv-kk-email-vorlage' );
		register_setting( 'muv-kk-settings-email', 'muv-kk-email-vorlage-bgcolor-body',
			array( self::class, 'vorlageBgColorBodyValidate' ) );

		
		add_settings_field( 'muv-kk-email-vorlage-bgcolor-content'
			, __( 'Hintergrundfarbe Inhalt', 'muv-kundenkonto' )
			, array( self::class, 'vorlageBgColorContentHtml' )
			, 'muv-kk-settings-email'
			, 'muv-kk-email-vorlage' );
		register_setting( 'muv-kk-settings-email', 'muv-kk-email-vorlage-bgcolor-content',
			array( self::class, 'vorlageBgColorContentValidate' ) );
	}

	
	public static function sectionAbsenderBeschreibung() {
		
	}

	
	public static function vonNameHtml() {
		$vonName = trim( sanitize_text_field( get_option( 'muv-kk-email-von-name', '' ) ) );
		if ( empty( $vonName ) ) {
			$vonName = DefaultSettings::EMAIL_VON_NAME();
		}

		echo '<input name="muv-kk-email-von-name" type="text" class="regular-text" maxlength="100" value="' . esc_html( $vonName ) . '" /> ';
		Tools::tooltip( __( 'So wird der Name des Absenders in den ausgehenden muv E-Mails angezeigt.', 'muv-kundenkonto' ) );
	}

	
	public static function vonNameValidate( $wert ) {
		$valid = trim( sanitize_text_field( $wert ) );
		if ( empty( $valid ) ) {
			$valid = get_option( 'muv-kk-email-von-name', DefaultSettings::EMAIL_VON_NAME() );
			add_settings_error( 'muv-kk-email-von-name'
				, 'muv-kk-email-von-name'
				, __( 'Bitte geben Sie einen Absender - Namen ein.', 'muv-kundenkonto' ) );
		}

		return $valid;
	}

	
	public static function vonEmailHtml() {
		$vonMail = trim( sanitize_email( get_option( 'muv-kk-email-von-mail', '' ) ) );
		if ( empty( $vonMail ) ) {
			$vonMail = DefaultSettings::EMAIL_VON_MAIL();
		}

		echo '<input name="muv-kk-email-von-mail" type="email" class="regular-text" maxlength="100" value="' . esc_html( $vonMail ) . '" /> ';
		Tools::tooltip( __( 'So wird die E-Mail Adresse des Absenders in den ausgehenden muv E-Mails angezeigt.',
			'muv-kundenkonto' ) );
	}

	
	public static function vonEmailValidate( $wert ) {
		$valid = trim( sanitize_email( $wert ) );
		if ( empty( $valid ) ) {
			$valid = get_option( 'muv-kk-email-von-mail', DefaultSettings::EMAIL_VON_MAIL() );
			add_settings_error( 'muv-kk-von-email'
				, 'muv-kk-von-email'
				, __( 'Bitte geben Sie eine Absender E-Mail ein.', 'muv-kundenkonto' ) );
		}

		return $valid;
	}

	
	public static function sectionVorlageBeschreibung() {
		_e( 'In diesem Abschnitt können Sie das Grund-Design der muv E-Mails anpassen.', 'muv-kundenkonto' );
	}

	
	public static function vorlageLogoHtml() {
		$logoUrl = get_option( 'muv-kk-email-vorlage-logo', '' );

		
		wp_enqueue_media();

		
		echo '<input type="hidden" id="muv-kk-email-vorlage-logo" name="muv-kk-email-vorlage-logo" value="' . esc_url( $logoUrl ) . '" />';
		
		echo '<button id="muv-kk-email-vorlage-logo-waehlen-button" class="button">' . __( 'Bild auswählen', 'muv-kundenkonto' ) . '</button>';

		echo ' ';

		
		echo '<button id="muv-kk-email-vorlage-logo-loeschen-button" class="button"><i class="fa fa-ban"></i> ' . __( 'Kein Bild verwenden',
				'muv-kundenkonto' ) . '</button>';
		Tools::tooltip( __( 'Dieses Bild (meist Logo) wird in der Kopfzeile der muv E-Mails angezeigt', 'muv-kundenkonto' ) );
		echo '<br>';
		echo '<br>';
		
		echo '<img id="muv-kk-email-vorlage-logo-preview" style="max-width:100%;min-height:30px;max-height:90px;';
		if ( empty( $logoUrl ) ) {
			echo 'display:none';
		}
		echo '" src="' . esc_url( $logoUrl ) . '" />';
	}

	
	public static function vorlageLogoValidate( $wert ) {
		
		return esc_url_raw( $wert );
	}

	
	public static function vorlageFooterHtml() {
		Tools::tooltip( __( 'Der Text, der in der Fußzeile aller muv E-Mails erscheint.<br>' .
		                    'Um den Anforderungen der deutschen Gesetzgebung zu entsprechen empfiehlt es sich, hier ' .
		                    'Ihr Impressum unter zu bringen.', 'muv-kundenkonto' ) );
		echo '<br>';
		$content = sanitize_textarea_field( get_option( 'muv-kk-email-vorlage-footer', '' ) );
		
		echo '<textarea class="large-text code" rows="10" cols="50" name="muv-kk-email-vorlage-footer">' . $content . '</textarea>';
	}

	
	public static function vorlageFooterValidate( $wert ) {
		$content = sanitize_textarea_field( $wert );

		

		return $content;
	}

	
	public static function vorlageColorHtml() {
		$val = sanitize_hex_color( get_option( 'muv-kk-email-vorlage-color', '' ) );
		if ( empty( $val ) ) {
			$val = DefaultSettings::EMAIL_VORLAGE_COLOR;
		}
		echo '<input type="text" name="muv-kk-email-vorlage-color" value="' . $val . '" class="muv-color-picker" >';
		Tools::tooltip( __( 'Die Grundfarbe für alle muv E-Mails. Wird in der Kopfzeile, bei den Buttons und Trennlinien verwendet.',
			'muv-kundenkonto' ) );
	}

	
	public static function vorlageColorValidate( $wert ) {
		$farbe = sanitize_hex_color( $wert );
		if ( empty( $farbe ) ) {
			$farbe = DefaultSettings::EMAIL_VORLAGE_COLOR;
			add_settings_error( 'muv-kk-email-vorlage-color'
				, 'muv-kk-email-vorlage-color'
				, __( 'Bitte geben Sie die Grundfarbe ein, die in allen muv E-Mails verwendet werden soll.', 'muv-kundenkonto' ) );
		}

		return $farbe;
	}

	
	public static function vorlageColorTextHtml() {
		$val = sanitize_hex_color( get_option( 'muv-kk-email-vorlage-color-text', '' ) );
		if ( empty( $val ) ) {
			$val = DefaultSettings::EMAIL_VORLAGE_COLOR_TEXT;
		}
		echo '<input type="text" name="muv-kk-email-vorlage-color-text" value="' . $val . '" class="muv-color-picker" >';
		Tools::tooltip( __( 'Die Textfarbe für alle muv E-Mails.', 'muv-kundenkonto' ) );
	}

	
	public static function vorlageColorTextValidate( $wert ) {
		$farbe = sanitize_hex_color( $wert );
		if ( empty( $farbe ) ) {
			$farbe = DefaultSettings::EMAIL_VORLAGE_COLOR_TEXT;
			add_settings_error( 'muv-kk-email-vorlage-color-text'
				, 'muv-kk-email-vorlage-color-text'
				, __( 'Bitte geben Sie die Textfarbe ein, die in allen muv E-Mails verwendet werden soll.', 'muv-kundenkonto' ) );
		}

		return $farbe;
	}

	
	public static function vorlageBgColorBodyHtml() {
		$val = sanitize_hex_color( get_option( 'muv-kk-email-vorlage-bgcolor-body', '' ) );
		if ( empty( $val ) ) {
			$val = DefaultSettings::EMAIL_VORLAGE_BGCOLOR_BODY;
		}
		echo '<input type="text" name="muv-kk-email-vorlage-bgcolor-body" value="' . $val . '" class="muv-color-picker" >';
		Tools::tooltip( __( 'Die Hintergrundfarbe für den Rand aller muv E-Mails.', 'muv-kundenkonto' ) );
	}

	
	public static function vorlageBgColorBodyValidate( $wert ) {
		$farbe = sanitize_hex_color( $wert );
		if ( empty( $farbe ) ) {
			$farbe = DefaultSettings::EMAIL_VORLAGE_BGCOLOR_BODY;
			add_settings_error( 'muv-kk-email-vorlage-bgcolor-body'
				, 'muv-kk-email-vorlage-bgcolor-body'
				,
				__( 'Bitte geben Sie die Hintergrundfarbe ein, die für den Rand in allen muv E-Mails verwendet werden soll.',
					'muv-kundenkonto' ) );
		}

		return $farbe;
	}

	
	public static function vorlageBgColorContentHtml() {
		$val = sanitize_hex_color( get_option( 'muv-kk-email-vorlage-bgcolor-content', '' ) );
		if ( empty( $val ) ) {
			$val = DefaultSettings::EMAIL_VORLAGE_BGCOLOR_CONTENT;
		}
		echo '<input type="text" name="muv-kk-email-vorlage-bgcolor-content" value="' . $val . '" class="muv-color-picker" >';
		Tools::tooltip( __( 'Die Hintergrundfarbe für den Inhalt aller muv E-Mails.', 'muv-kundenkonto' ) );
	}

	
	public static function vorlageBgColorContentValidate( $wert ) {
		$farbe = sanitize_hex_color( $wert );
		if ( empty( $farbe ) ) {
			$farbe = DefaultSettings::EMAIL_VORLAGE_BGCOLOR_CONTENT;
			add_settings_error( 'muv-kk-email-vorlage-bgcolor-content'
				, 'muv-kk-email-vorlage-bgcolor-content'
				,
				__( 'Bitte geben Sie die Hintergrundfarbe ein, die für den Textbereich in allen muv E-Mails verwendet werden soll.',
					'muv-kundenkonto' ) );
		}

		return $farbe;
	}
}
