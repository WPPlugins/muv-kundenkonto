<?php


namespace muv\KundenKonto\Admin\Settings;

use \muv\KundenKonto\Classes\DefaultSettings;


defined( 'ABSPATH' ) OR exit;


class Benachrichtigungen {

	private static $settingsPage = '';

	
	public static function init() {
		
		self::$settingsPage = 'allgemein';
		$page               = sanitize_text_field( filter_input( INPUT_GET, 'typ' ) );
		if ( empty( $page ) ) {
			$page = sanitize_text_field( filter_input( INPUT_POST, 'typ' ) );
		}
		if ( ( $page === 'pwd-vergessen' ) || ( $page === 'konto-aktivieren' ) ||
		     ( $page === 'pwd-geaendert' ) || ( $page === 'email-aktivieren' ) ||
		     ( $page === 'email-geaendert' || ( $page === 'konto-loeschen' ) )
		) {
			self::$settingsPage = $page;
		}

		
		add_action( 'admin_init', array( self::class, 'addSettingsBenachrichtigungen' ) );

		
		add_action( 'admin_init', array( self::class, 'addSettingsBenachrichtigung' ) );
	}

	
	public static function handleSettings() {
		
		if ( self::$settingsPage === 'allgemein' ) {
			

			
			echo '<form method="post" action="options.php">';

			
			settings_fields( 'muv-kk-settings-benachrichtigungen' );

			
			do_settings_sections( 'muv-kk-settings-benachrichtigungen' );
		} else {
			

			
			echo '<br><a href="admin.php?page=muv-kk-einstellungen&tab=benachrichtigungen" class="button"><i class="fa fa-chevron-circle-left"> </i> ' .
			     __( 'Zurück zur Übersicht', 'muv-kundenkonto' ) . '</a><br><br>';

			
			echo '<form method="post" action="options.php">';

			
			settings_fields( 'muv-kk-settings-email-vorlage' );

			
			echo '<input type="hidden" name="typ" value="' . esc_html( self::$settingsPage ) . '">';

			
			do_settings_sections( 'muv-kk-settings-email-vorlage' );

			
			submit_button();
		}
	}

	
	public static function addSettingsBenachrichtigungen() {
		
		add_settings_section( 'muv-kk-benachrichtigungen', __( 'Benachrichtigungen', 'muv-kundenkonto' ), array(
			self::class,
			'sectionBenachrichtigungenBeschreibung'
		), 'muv-kk-settings-benachrichtigungen' );
	}

	
	public static function addSettingsBenachrichtigung() {
		
		$text = '';
		switch ( self::$settingsPage ) {
			case 'pwd-vergessen':
				$text = __( 'Passwort vergessen', 'muv-kundenkonto' );
				break;
			case 'konto-aktivieren':
				$text = __( 'Konto aktivieren', 'muv-kundenkonto' );
				break;
			case 'pwd-geaendert':
				$text = __( 'Passwort geändert', 'muv-kundenkonto' );
				break;
			case 'email-aktivieren':
				$text = __( 'E-Mail aktivieren', 'muv-kundenkonto' );
				break;
			case 'email-geaendert':
				$text = __( 'E-Mail geändert', 'muv-kundenkonto' );
				break;
			case 'konto-loeschen':
				$text = __( 'Konto löschen', 'muv-kundenkonto' );
				break;
		}

		add_settings_section( 'muv-kk-email-vorlage'
			, $text
			, array( self::class, 'sectionEMailVorlageBeschreibung' )
			, 'muv-kk-settings-email-vorlage' );

		
		add_settings_field( 'muv-kk-email-vorlage-' . self::$settingsPage . '-betreff'
			, __( 'Betreff', 'muv-kundenkonto' )
			, array( self::class, 'eMailVorlageBetreffHtml' )
			, 'muv-kk-settings-email-vorlage'
			, 'muv-kk-email-vorlage' );
		register_setting( 'muv-kk-settings-email-vorlage', 'muv-kk-email-vorlage-' . self::$settingsPage . '-betreff', array(
			self::class,
			'eMailVorlageBetreffValidate'
		) );

		
		add_settings_field( 'muv-kk-email-vorlage-' . self::$settingsPage . '-typ'
			, __( 'Typ', 'muv-kundenkonto' )
			, array( self::class, 'eMailVorlageTypHtml' )
			, 'muv-kk-settings-email-vorlage'
			, 'muv-kk-email-vorlage' );
		register_setting( 'muv-kk-settings-email-vorlage', 'muv-kk-email-vorlage-' . self::$settingsPage . '-typ', array(
			self::class,
			'eMailVorlageTypValidate'
		) );

		
		add_settings_field( 'muv-kk-email-vorlage-' . self::$settingsPage . '-html'
			, __( 'Inhalt (HTML)', 'muv-kundenkonto' )
			, array( self::class, 'eMailVorlageHtmlHtml' )
			, 'muv-kk-settings-email-vorlage'
			, 'muv-kk-email-vorlage' );
		register_setting( 'muv-kk-settings-email-vorlage', 'muv-kk-email-vorlage-' . self::$settingsPage . '-html', array(
			self::class,
			'eMailVorlageHtmlValidate'
		) );

		
		add_settings_field( 'muv-kk-email-vorlage-' . self::$settingsPage . '-text'
			, __( 'Inhalt (Nur Text)', 'muv-kundenkonto' )
			, array( self::class, 'eMailVorlageTextHtml' )
			, 'muv-kk-settings-email-vorlage'
			, 'muv-kk-email-vorlage' );
		register_setting( 'muv-kk-settings-email-vorlage', 'muv-kk-email-vorlage-' . self::$settingsPage . '-text', array(
			self::class,
			'eMailVorlageTextValidate'
		) );
	}

	
	public static function sectionBenachrichtigungenBeschreibung() {
		_e( 'Die Benachrichtigungen des <b>muv Kundenkonto</b> sind unten aufgelistet. Klicken Sie auf das Zahnrad (rechts) um sie zu konfigurieren.', 'muv-kundenkonto' );
		echo '<table class="datatable-min display" cellspacing="0" width="100%">
	    <thead>
		<tr>
		    <th>' . __( 'Benachrichtigung', 'muv-kundenkonto' ) . '</th>
		    <th>' . __( 'Inhaltstyp', 'muv-kundenkonto' ) . '</th>
		    <th>' . __( 'Empfänger', 'muv-kundenkonto' ) . '</th>
		    <th></th>
		</tr>
	    </thead>
	    <tbody>
		<tr>
		    <td><a href="?page=muv-kk-einstellungen&tab=benachrichtigungen&typ=konto-aktivieren">' . __( 'Konto aktivieren.', 'muv-kundenkonto' ) . '</a><br><small> ' .
		     __( 'Wird versendet, wenn der Kunde sein Konto angelegt hat. Die E-Mail beinhaltet einen Bestätigunslink zum Aktivieren des Kontos.', 'muv-kundenkonto' ) . '</small></td>
		    <td>' . self::getInhaltsTypText( 'konto-aktivieren' ) . '</td>
		    <td>Kunde</td>
		    <td><a class="button" href="?page=muv-kk-einstellungen&tab=benachrichtigungen&typ=konto-aktivieren"><i class="fa fa-fw fa-cog"> </i></a></td>
		</tr>
		<tr>
		    <td><a href="?page=muv-kk-einstellungen&tab=benachrichtigungen&typ=pwd-vergessen">' . __( 'Passwort vergessen', 'muv-kundenkonto' ) . '</a><br><small>' .
		     __( 'Wird versendet, wenn der Kunde beim Login angibt, sein Passwort vergessen zu haben.', 'muv-kundenkonto' ) . '</small></td>
		    <td>' . self::getInhaltsTypText( 'pwd-vergessen' ) . '</td>
		    <td>Kunde</td>
		    <td><a class="button" href="?page=muv-kk-einstellungen&tab=benachrichtigungen&typ=pwd-vergessen"><i class="fa fa-fw fa-cog"> </i></a></td>
		</tr>
		<tr>
		    <td><a href="?page=muv-kk-einstellungen&tab=benachrichtigungen&typ=pwd-geaendert">' . __( 'Passwort geändert', 'muv-kundenkonto' ) . '</a><br><small>' .
		     __( 'Wird versendet, wenn der Kunde sein Passwort geändert hat.', 'muv-kundenkonto' ) . '</small></td>
		    <td>' . self::getInhaltsTypText( 'pwd-geaendert' ) . '</td>
		    <td>Kunde</td>
		    <td><a class="button" href="?page=muv-kk-einstellungen&tab=benachrichtigungen&typ=pwd-geaendert"><i class="fa fa-fw fa-cog"> </i></a></td>
		</tr>
		<tr>
		    <td><a href="?page=muv-kk-einstellungen&tab=benachrichtigungen&typ=email-aktivieren">' . __( 'E-Mail aktivieren', 'muv-kundenkonto' ) . '</a><br><small>' .
		     __( 'Wird versendet, wenn der Kunde seine E-Mail Adresse ändern möchte, diese jedoch noch bestätigt werden muss. Die E-Mail beinhaltet einen Bestätigungslink.', 'muv-kundenkonto' ) . '</small></td>
		    <td>' . self::getInhaltsTypText( 'email-aktivieren' ) . '</td>
		    <td>Kunde</td>
		    <td><a class="button" href="?page=muv-kk-einstellungen&tab=benachrichtigungen&typ=email-aktivieren"><i class="fa fa-fw fa-cog"> </i></a></td>
		</tr>
		<tr>
		    <td><a href="?page=muv-kk-einstellungen&tab=benachrichtigungen&typ=email-geaendert">' . __( 'E-Mail geändert', 'muv-kundenkonto' ) . '</a><br><small>' .
		     __( 'Wird versendet, wenn die neue E-Mail Adresse des Kunden bestätigt und damit im System gespeichert wurde.', 'muv-kundenkonto' ) . '</small></td>
		    <td>' . self::getInhaltsTypText( 'email-geaendert' ) . '</td>
		    <td>Kunde</td>
		    <td><a class="button" href="?page=muv-kk-einstellungen&tab=benachrichtigungen&typ=email-geaendert"><i class="fa fa-fw fa-cog"> </i></a></td>
		</tr>
		<tr>
		    <td><a href="?page=muv-kk-einstellungen&tab=benachrichtigungen&typ=konto-loeschen">' . __( 'Konto löschen', 'muv-kundenkonto' ) . '</a><br><small>' .
		     __( 'Wird versendet, wenn der Kunde sein Konto unwiderbringlich löschen möchte. Die E-Mail beinhaltet einen Bestätigungslink zum Löschen.', 'muv-kundenkonto' ) . '</small></td>
		    <td>' . self::getInhaltsTypText( 'konto-loeschen' ) . '</td>
		    <td>Kunde</td>
		    <td><a class="button" href="?page=muv-kk-einstellungen&tab=benachrichtigungen&typ=konto-loeschen"><i class="fa fa-fw fa-cog"> </i></a></td>
		</tr>
	    </tbody>
	</table>';
	}

	
	private static function getInhaltsTypText( $email ) {
		$val = (int) ( get_option( 'muv-kk-email-vorlage-' . $email . '-typ', '' ) );
		switch ( $val ) {
			case 1:
				return __( 'Nur Text', 'muv-kundenkonto' );
			case 2:
				return __( 'Nur HTML', 'muv-kundenkonto' );
			case 3:
				return __( 'Multipart (Text + HTML)', 'muv-kundenkonto' );
		}

		return '???'; 	}

	
	public static function sectionEMailVorlageBeschreibung() {
		switch ( self::$settingsPage ) {
			case 'pwd-vergessen':
				_e( 'Wird versendet, wenn der Kunde beim Login angibt, sein Passwort vergessen zu haben.', 'muv-kundenkonto' );
				break;
			case 'konto-aktivieren':
				_e( 'Wird versendet, wenn der Kunde sein Konto angelegt hat. Diese Mail beinhaltet einen Bestätigunslink zum Aktivieren des Kontos.', 'muv-kundenkonto' );
				break;
			case 'pwd-geaendert':
				_e( 'Wird versendet, wenn der Kunde sein Passwort geändert hat.', 'muv-kundenkonto' );
				break;
			case 'email-aktivieren':
				_e( 'Wird versendet, wenn der Kunde seine E-Mail Adresse ändern möchte, diese jedoch noch bestätigt werden muss. Die E-Mail beinhaltet einen Bestätigungslink.', 'muv-kundenkonto' );
				break;
			case 'email-geaendert':
				_e( 'Wird versendet, wenn die neue E-Mail Adresse des Kunden bestätigt und damit im System gespeichert wurde.', 'muv-kundenkonto' );
				break;
			case 'konto-loeschen':
				_e( 'Wird versendet, wenn der Kunde sein Konto unwiderbringlich löschen möchte. Die E-Mail beinhaltet einen Bestätigungslink zum Löschen.', 'muv-kundenkonto' );
				break;
		}
	}

	
	public static function eMailVorlageBetreffHtml() {
		$val = trim( sanitize_text_field( get_option( 'muv-kk-email-vorlage-' . self::$settingsPage . '-betreff', '' ) ) );
		if ( empty( $val ) ) {
			switch ( self::$settingsPage ) {
				case 'konto-aktivieren':
					$val = DefaultSettings::EMAIL_VORLAGE_KONTO_AKTIVIEREN_BETREFF;
					break;
				case 'pwd-vergessen':
					$val = DefaultSettings::EMAIL_VORLAGE_PWD_VERGESSEN_BETREFF;
					break;
				case 'pwd-geaendert':
					$val = DefaultSettings::EMAIL_VORLAGE_PWD_GEAENDERT_BETREFF;
					break;
				case 'email-aktivieren':
					$val = DefaultSettings::EMAIL_VORLAGE_EMAIL_AKTIVIEREN_BETREFF;
					break;
				case 'email-geaendert':
					$val = DefaultSettings::EMAIL_VORLAGE_EMAIL_GEAENDERT_BETREFF;
					break;
				case 'konto-loeschen':
					$val = DefaultSettings::EMAIL_VORLAGE_KONTO_LOESCHEN_BETREFF;
					break;
				default:
					$val = ''; 					break;
			}
		}
		echo '<input type="text" name="muv-kk-email-vorlage-' . self::$settingsPage . '-betreff" value="' . esc_html( $val ) . '" class="regular-text">';
	}

	
	public static function eMailVorlageBetreffValidate( $wert ) {
		$betreff = trim( sanitize_text_field( $wert ) );
		if ( empty( trim( $betreff ) ) ) {
			switch ( self::$settingsPage ) {
				case 'konto-aktivieren':
					$betreff = DefaultSettings::EMAIL_VORLAGE_KONTO_AKTIVIEREN_BETREFF;
					break;
				case 'pwd-vergessen':
					$betreff = DefaultSettings::EMAIL_VORLAGE_PWD_VERGESSEN_BETREFF;
					break;
				case 'pwd-geaendert':
					$betreff = DefaultSettings::EMAIL_VORLAGE_PWD_GEAENDERT_BETREFF;
					break;
				case 'email-aktivieren':
					$betreff = DefaultSettings::EMAIL_VORLAGE_EMAIL_AKTIVIEREN_BETREFF;
					break;
				case 'email-geaendert':
					$betreff = DefaultSettings::EMAIL_VORLAGE_EMAIL_GEAENDERT_BETREFF;
					break;
				case 'konto-loeschen':
					$betreff = DefaultSettings::EMAIL_VORLAGE_KONTO_LOESCHEN_BETREFF;
					break;
				default:
					$betreff = ''; 					break;
			}
			add_settings_error( 'muv-kk-email-vorlage-betreff'
				, 'muv-kk-email-vorlage-betreff'
				, __( 'Bitte geben Sie den Betreff ein, der für diese E-Mail Vorlage verwendet werden soll.', 'muv-kundenkonto' ) );
		}

		return $betreff;
	}

	
	public static function eMailVorlageTypHtml() {
		$val = (int) ( get_option( 'muv-kk-email-vorlage-' . self::$settingsPage . '-typ', '' ) );
		if ( empty( $val ) ) {
			$val = DefaultSettings::EMAIL_VORLAGE_TYP;
		}
		echo '<select name="muv-kk-email-vorlage-' . self::$settingsPage . '-typ">';
		echo '<option value="1" ' . selected( $val, 1 ) . '>' . __( 'Nur Text', 'muv-kundenkonto' ) . '</option>';
		echo '<option value="2" ' . selected( $val, 2 ) . '>' . __( 'Nur HTML', 'muv-kundenkonto' ) . '</option>';
		echo '<option value="3" ' . selected( $val, 3 ) . '>' . __( 'Multipart (Text + HTML)', 'muv-kundenkonto' ) . '</option>';
		echo '</select>';
	}

	
	public static function eMailVorlageTypValidate( $wert ) {
		$typ = (int) ( $wert );
		if ( empty( $typ ) ) {
			
			$typ = DefaultSettings::EMAIL_VORLAGE_TYP;
			add_settings_error( 'muv-kk-email-vorlage-typ'
				, 'muv-kk-email-vorlage-typ'
				, __( 'Bitte geben Sie den E-Mail - Typ an, der für diese E-Mail Vorlage verwendet werden soll.', 'muv-kundenkonto' ) );
		}

		return $typ;
	}

	
	public static function eMailVorlageHtmlHtml() {
		$content = trim( get_option( 'muv-kk-email-vorlage-' . self::$settingsPage . '-html', '' ) );
		if ( empty( $content ) ) {
			switch ( self::$settingsPage ) {
				case 'konto-aktivieren':
					$content = DefaultSettings::EMAIL_VORLAGE_KONTO_AKTIVIEREN_HTML;
					break;
				case 'pwd-vergessen':
					$content = DefaultSettings::EMAIL_VORLAGE_PWD_VERGESSEN_HTML;
					break;
				case 'pwd-geaendert':
					$content = DefaultSettings::EMAIL_VORLAGE_PWD_GEAENDERT_HTML;
					break;
				case 'email-aktivieren':
					$content = DefaultSettings::EMAIL_VORLAGE_EMAIL_AKTIVIEREN_HTML;
					break;
				case 'email-geaendert':
					$content = DefaultSettings::EMAIL_VORLAGE_EMAIL_GEAENDERT_HTML;
					break;
				case 'konto-loeschen':
					$content = DefaultSettings::EMAIL_VORLAGE_KONTO_LOESCHEN_HTML;
					break;
				default:
					$content = ''; 					break;
			}
		}

		
		$editor_id = 'muvkvemailvorlagehtml';
		
		$settings = array(
			'media_buttons' => false,
			'textarea_name' => 'muv-kk-email-vorlage-' . self::$settingsPage . '-html',
			'textarea_rows' => 10,
			'tinymce'       => array(
				'content_css' => MUV_KK_URL . '/assets/css/settings-email-editor.css'
			)
		);
		
		wp_editor( $content, $editor_id, $settings );
	}

	
	public static function eMailVorlageHtmlValidate( $wert ) {
		
		$content = trim( $wert );
		if ( empty( $content ) ) {
			switch ( self::$settingsPage ) {
				case 'konto-aktivieren':
					$content = DefaultSettings::EMAIL_VORLAGE_KONTO_AKTIVIEREN_HTML;
					break;
				case 'pwd-vergessen':
					$content = DefaultSettings::EMAIL_VORLAGE_PWD_VERGESSEN_HTML;
					break;
				case 'pwd-geaendert':
					$content = DefaultSettings::EMAIL_VORLAGE_PWD_GEAENDERT_HTML;
					break;
				case 'email-aktivieren':
					$content = DefaultSettings::EMAIL_VORLAGE_EMAIL_AKTIVIEREN_HTML;
					break;
				case 'email-geaendert':
					$content = DefaultSettings::EMAIL_VORLAGE_EMAIL_GEAENDERT_HTML;
					break;
				case 'konto-loeschen':
					$content = DefaultSettings::EMAIL_VORLAGE_KONTO_LOESCHEN_HTML;
					break;
				default:
					$content = ''; 					break;
			}
			add_settings_error( 'muv-kk-email-vorlage-html'
				, 'muv-kk-email-vorlage-html'
				, __( 'Bitte geben Sie den HTML - Text an, der für diese E-Mail Vorlage verwendet werden soll.', 'muv-kundenkonto' ) );
		}

		return $content;
	}

	
	public static function eMailVorlageTextHtml() {
		$content = trim( sanitize_textarea_field( get_option( 'muv-kk-email-vorlage-' . self::$settingsPage . '-text', '' ) ) );
		if ( empty( $content ) ) {
			switch ( self::$settingsPage ) {
				case 'konto-aktivieren':
					$content = DefaultSettings::EMAIL_VORLAGE_KONTO_AKTIVIEREN_TEXT;
					break;
				case 'pwd-vergessen':
					$content = DefaultSettings::EMAIL_VORLAGE_PWD_VERGESSEN_TEXT;
					break;
				case 'pwd-geaendert':
					$content = DefaultSettings::EMAIL_VORLAGE_PWD_GEAENDERT_TEXT;
					break;
				case 'email-aktivieren':
					$content = DefaultSettings::EMAIL_VORLAGE_EMAIL_AKTIVIEREN_TEXT;
					break;
				case 'email-geaendert':
					$content = DefaultSettings::EMAIL_VORLAGE_EMAIL_GEAENDERT_TEXT;
					break;
				case 'konto-loeschen':
					$content = DefaultSettings::EMAIL_VORLAGE_KONTO_LOESCHEN_TEXT;
					break;
				default:
					$content = ''; 					break;
			}
		}

		echo '<textarea class="large-text code" rows="10" cols="50" name="muv-kk-email-vorlage-' . self::$settingsPage . '-text">' . $content . '</textarea>';
	}

	
	public static function eMailVorlageTextValidate( $wert ) {
		$content = trim( sanitize_textarea_field( $wert ) );
		if ( empty( $content ) ) {
			switch ( self::$settingsPage ) {
				case 'konto-aktivieren':
					$content = DefaultSettings::EMAIL_VORLAGE_KONTO_AKTIVIEREN_TEXT;
					break;
				case 'pwd-vergessen':
					$content = DefaultSettings::EMAIL_VORLAGE_PWD_VERGESSEN_TEXT;
					break;
				case 'pwd-geaendert':
					$content = DefaultSettings::EMAIL_VORLAGE_PWD_GEAENDERT_TEXT;
					break;
				case 'email-aktivieren':
					$content = DefaultSettings::EMAIL_VORLAGE_EMAIL_AKTIVIEREN_TEXT;
					break;
				case 'email-geaendert':
					$content = DefaultSettings::EMAIL_VORLAGE_EMAIL_GEAENDERT_TEXT;
					break;
				case 'konto-loeschen':
					$content = DefaultSettings::EMAIL_VORLAGE_KONTO_LOESCHEN_TEXT;
					break;
				default:
					$content = ''; 					break;
			}
			add_settings_error( 'muv-kk-email-vorlage-text'
				, 'muv-kk-email-vorlage-text'
				, __( 'Bitte geben Sie den Text an, der für diese E-Mail Vorlage verwendet werden soll.', 'muv-kundenkonto' ) );
		}

		return $content;
	}

}
