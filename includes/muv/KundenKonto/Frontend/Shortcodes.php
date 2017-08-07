<?php

namespace muv\KundenKonto\Frontend;

use muv\KundenKonto\Classes\Tools;
use muv\KundenKonto\Classes\Auth;
use muv\KundenKonto\Classes\Flash;


defined( 'ABSPATH' ) OR exit;


class Shortcodes {

	
	public static function init() {
				add_shortcode( 'muv-kk-kunde-ist-angemeldet', array( self::class, 'shortcodeKundeIstAngemeldet' ) );
				add_shortcode( 'muv-kk-kunde-vorname', array( self::class, 'shortcodeKundeVorname' ) );
				add_shortcode( 'muv-kk-kunde-nachname', array( self::class, 'shortcodeKundeNachname' ) );
				add_shortcode( 'muv-kk-aendere-pwt', array( self::class, 'shortcodeAenderePasswort' ) );
				add_shortcode( 'muv-kk-aendere-email', array( self::class, 'shortcodeAendereEmail' ) );
				add_shortcode( 'muv-kk-loesche-konto', array( self::class, 'shortcodeLoescheKonto' ) );
	}

	
	public static function shortcodeKundeIstAngemeldet( $attr, $content ) {
		
		$html = '';

		
		$action = filter_input( INPUT_GET, 'muv-kk-aktion', FILTER_SANITIZE_STRING );

		switch ( $action ) {
			case 'bestaetige-email'  :
								$token = filter_input( INPUT_GET, 'muv-kk-token' );
								$email = filter_input( INPUT_GET, 'muv-kk-email', FILTER_VALIDATE_EMAIL );

				$kunde = Auth::getKundeMitEMail( $email );
				if ( empty( $kunde ) ) {
					$res = - 1;
				} else {
					$res = $kunde->aktiviereNeueEmail( $token );
				}


				if ( $res !== true ) {
					switch ( $res ) {
						case - 1:
							Flash::addMessage( __( 'Der von Ihnen verwendete Bestätigungs-Link ist entweder falsch oder veraltet.', 'muv-kundenkonto' ), 'warning' );
							break;
						case - 2:
							Flash::addMessage( __( 'Die von Ihnen gewünschte E-Mail Adresse ist bereits vorhanden und kann deshalb nicht geändert werden.', 'muv-kundenkonto' ), 'warning' );
							break;
					}
				} else {
										Flash::addMessage( __( 'Ihre E-Mail Adresse wurde erfolgreich geändert. Sie wurden aus Sicherheitsgründen abgemeldet. Bitte denken Sie daran, sich ab sofort mit der neuen E-Mail Adresse anzumelden.', 'muv-kundenkonto' ), 'success' );
					
					$kunde->logout();
				}
				break;
			case 'loesche-konto' :
								$token = filter_input( INPUT_GET, 'muv-kk-token' );
								$email = filter_input( INPUT_GET, 'muv-kk-email', FILTER_VALIDATE_EMAIL );

				$kunde = Auth::getKundeMitEMail( $email );
				if ( empty( $kunde ) ) {
					$res = - 1;
				} else {
															$res = Auth::loescheKonto( $email, $token );
				}

				if ( $res !== true ) {
					switch ( $res ) {
						case - 1:
							Flash::addMessage( __( 'Der von Ihnen verwendete Löschungs-Link ist entweder falsch oder veraltet.', 'muv-kundenkonto' ), 'warning' );
							break;
					}
				} else {
					Flash::addMessage( __( 'Ihr Konto wurde unwiderbringlich gelöscht!', 'muv-kundenkonto' ), 'success' );
					
					$html .= Tools::getTemplatePartContent( 'Frontend/Auth/konto-geloescht.tpl.php' );

					return $html;
				}
				break;
			case 'aktiviere-konto':
				$email = filter_input( INPUT_GET, 'muv-kk-email', FILTER_VALIDATE_EMAIL );
				$token = filter_input( INPUT_GET, 'muv-kk-token', FILTER_SANITIZE_STRING );
				$res   = Auth::bestaetigeEmail( $email, $token );
				if ( $res !== true ) {
					switch ( $res ) {
						case - 2:
							Flash::addMessage( __( 'Ihr Zugang wurde bereits aktiviert. Sie können sich jederzeit mit Ihren Zugangsdaten anmelden.' ), 'info' );
							break;
						default :
							Flash::addMessage( __( 'Der von Ihnen verwendete Bestätigungs-Link ist entweder falsch oder veraltet.', 'muv-kundenkonto' ), 'warning' );
							break;
					}
				} else {
					Flash::addMessage( __( 'Ihr Zugang wurde erfolgreich aktiviert.<br>Sie können sich ab sofort mit den von Ihnen gewählten Daten am System anmelden.', 'muv-kundenkonto' ), 'success' );
				}

				
				$kunde = Auth::getLoggedInKunde( true );
				if ( ! empty( $kunde ) ) {
					$kunde->logout();
				}

				break;
		}

		
		if ( $action === 'aendere-vergessenes-pwt' ) {
			$kunde = Auth::getLoggedInKunde( true );
			if ( ! empty( $kunde ) ) {
				$kunde->logout();
			}
		}


		
		if ( ( ! empty( $attr['pseudo-login'] ) ) && ( $attr['pseudo-login'] == 1 ) ) {
			$pseudo = true;
		} else {
			$pseudo = false;
		}

		if ( ! empty( Auth::getLoggedInKunde( $pseudo ) ) ) {
			
			$html .= '<div>' . do_shortcode( $content ) . '</div>';
		} else {
			

			
			if ( ! empty( $attr['msg'] ) ) {
				$html .= '<div class="muv-kundenkonto-msg">' . $attr['msg'] . '</div>';
			}

			
			if ( ( ! empty( $attr['show-login'] ) ) && ( $attr['show-login'] == 1 ) ) {
				
								$action = filter_input( INPUT_GET, 'muv-kk-aktion', FILTER_SANITIZE_STRING );

				
				$var['link-login'] = '/muv-kundenkonto/login';
				
				$var['link-seite']          = remove_query_arg( [
					'muv-kk-aktion',
					'muv-kk-email',
					'muv-kk-token'
				], Tools::getPageUrl( true ) );
				$var['link-erstelle-konto'] = add_query_arg( 'muv-kk-aktion', 'erstelle-konto', $var['link-seite'] );
				$var['link-pwt-vergessen']  = add_query_arg( 'muv-kk-aktion', 'pwt-vergessen', $var['link-seite'] );

				

				
				$var['login']    = '';
				$var['passwort'] = '';
				$var['vorname']  = '';
				$var['nachname'] = '';

				
				$var['passwort-1'] = '';
				$var['passwort-2'] = '';


				switch ( $action ) {
					case 'erstelle-konto':
						
						if ( filter_input( INPUT_POST, 'muv-kk-login' ) !== null ) {
														$var['login'] = filter_input( INPUT_POST, 'muv-kk-login', FILTER_VALIDATE_EMAIL );
														$var['passwort'] = filter_input( INPUT_POST, 'muv-kk-passwort' );
														$var['vorname'] = filter_input( INPUT_POST, 'muv-kk-vorname', FILTER_SANITIZE_STRING );
														$var['nachname'] = filter_input( INPUT_POST, 'muv-kk-nachname', FILTER_SANITIZE_STRING );

							$res = Auth::erzeugeKundenZugang( $var['login'], $var['vorname'], $var['nachname'], $var['passwort'] );

							if ( $res !== true ) {
								switch ( $res ) {
									case - 1:
										Flash::addMessage( __( 'Bitte geben Sie eine gültige E-Mail Adresse ein.', 'muv-kundenkonto' ), 'danger' );
										Flash::setValue( 'muv-kk.auth.erstelle-konto.login.error', 'has-error' );
										break;
									case - 2:
										Flash::addMessage( __( 'Bitte geben Sie ein mindestens 8 stelliges Passwort ein.', 'muv-kundenkonto' ), 'danger' );
										Flash::setValue( 'muv-kk.auth.erstelle-konto.passwort.error', 'has-error' );
										break;
									case - 3:
										Flash::addMessage( __( 'Dieser Zugang existiert bereits in unserem System, er wurde aber noch nicht aktiviert.<br>Wir haben Ihnen gerade eben die Bestätigungs-Mail erneut zugesendet.', 'muv-kundenkonto' ), 'info' );
										break;
									case - 4:
										Flash::addMessage( __( 'Dieser Zugang existiert bereits in unserem System. Haben Sie evtl. Ihr Passwort vergessen?', 'muv-kundenkonto' ), 'danger' );
										break;
									default :
										Flash::addMessage( __( 'Es tut uns leid, beim Versuch Ihren Zugang zu erstellen ist leider ein Fehler aufgetreten. Bitte versuchen Sie es in ein paar Minuten erneut.', 'muv-kundenkonto' ), 'danger' );
										break;
								}
							} else {
																Flash::addMessage( __( 'Sie haben es fast geschafft! Wir haben Ihnen gerade eben eine E-Mail gesendet. Bitte klicken Sie auf den darin enthaltenen Link, um Ihren Zugang zu aktivieren.', 'muv-kundenkonto' ), 'info' );
								
								$html .= Tools::getTemplatePartContent( 'Frontend/Auth/login.tpl.php', $var );
								break;
							}
						}
						$html .= Tools::getTemplatePartContent( 'Frontend/Auth/erstelle-konto.tpl.php', $var );
						break;

					case 'pwt-vergessen':
						
						if ( filter_input( INPUT_POST, 'muv-kk-login' ) !== null ) {
														$var['login'] = filter_input( INPUT_POST, 'muv-kk-login', FILTER_VALIDATE_EMAIL );

							$res = Auth::sendePwdVergessenEmail( $var['login'] );

							if ( $res !== true ) {
								switch ( $res ) {
									case - 1:
										Flash::addMessage( __( 'Bitte geben Sie eine gültige E-Mail Adresse ein.', 'muv-kundenkonto' ), 'danger' );
										Flash::setValue( 'muv-kk.auth.pwt-vergessen.login.error', 'has-error' );
										break;
									case - 2:
										Flash::addMessage( __( 'Ein Zugang mit dieser E-Mail Adresse existiert nicht in unserem System.', 'muv-kundenkonto' ), 'warning' );
										Flash::setValue( 'muv-kk.auth.pwt-vergessen.login.error', 'has-error' );
										break;
									case - 3:
										Flash::addMessage( __( 'Der Zugang mit dieser E-Mail wurde noch nicht bestätigt.<br>Wir haben Ihnen gerade eben die Bestätigungs E-Mail erneut zugesendet.', 'muv-kundenkonto' ), 'info' );
										break;
									default :
										Flash::addMessage( __( 'Beim Senden der E-Mail zum Ändern Ihres Passwortes ist ein Fehler aufgetreten.<br>Bitte versuchen Sie es in ein paar Minuten erneut!', 'muv-kundenkonto' ), 'danger' );
										break;
								}
							} else {
								Flash::addMessage( __( 'Wir haben Ihnen eine E-Mail gesendet, mit der Sie ein neues Passwort vergeben können.', 'muv-kundenkonto' ), 'info' );
							}
						}
						$html .= Tools::getTemplatePartContent( 'Frontend/Auth/pwt-vergessen.tpl.php', $var );
						break;

					case 'aendere-vergessenes-pwt':
						
						$email = filter_input( INPUT_GET, 'muv-kk-email', FILTER_VALIDATE_EMAIL );
						$token = filter_input( INPUT_GET, 'muv-kk-token', FILTER_SANITIZE_STRING );

						
						if ( filter_input( INPUT_POST, 'muv-kk-passwort-1' ) !== null ) {
														$var['passwort-1'] = filter_input( INPUT_POST, 'muv-kk-passwort-1' );
							$var['passwort-2'] = filter_input( INPUT_POST, 'muv-kk-passwort-2' );

							$res = Auth::aendereVegessenesPwd( $email, $token, $var['passwort-1'], $var['passwort-2'] );

							if ( $res !== true ) {
								switch ( $res ) {
									case - 1:
										Flash::addMessage( __( 'Bitte geben Sie ein mindestens 8 stelliges Passwort ein.', 'muv-kundenkonto' ), 'danger' );
										Flash::setValue( 'muv-kk.auth.aendere-vergesssenes-pwt.passwort-1.error', 'has-error' );
										break;
									case - 2:
										Flash::addMessage( __( 'Ihr neues Passwort stimmt nicht mit seiner Bestätigung überein.', 'muv-kundenkonto' ), 'danger' );
										Flash::setValue( 'muv-kk.auth.aendere-vergesssenes-pwt.passwort-1.error', 'has-error' );
										Flash::setValue( 'muv-kk.auth.aendere-vergesssenes-pwt.passwort-2.error', 'has-error' );
										break;
									case - 3:
										Flash::addMessage( __( 'Der von Ihnen verwendete Link zum Ändern des Passworts ist entweder falsch oder veraltet.', 'muv-kundenkonto' ), 'danger' );
										break;
								}
							} else {
								Flash::addMessage( __( 'Wir haben Ihr Passwort geändert. Sie können sich ab sofort mit diesem neuen Passwort anmelden.', 'muv-kundenkonto' ), 'info' );
																$html .= Tools::getTemplatePartContent( 'Frontend/Auth/login.tpl.php', $var );
								break;
							}
						}
						$html .= Tools::getTemplatePartContent( 'Frontend/Auth/aendere-vergessenes-pwt.tpl.php', $var );
						break;

					default:
						
						$html .= Tools::getTemplatePartContent( 'Frontend/Auth/login.tpl.php', $var );
						break;
				}
			}
		}

		

		return $html;
	}

	
	public
	static function shortcodeKundeVorname(
		$attr
	) {
		
		$html = '';

		
		if ( ( ! empty( $attr['pseudo-login'] ) ) && ( $attr['pseudo-login'] == 1 ) ) {
			$pseudo = true;
		} else {
			$pseudo = false;
		}

		$kunde = Auth::getLoggedInKunde( $pseudo );

		if ( ! empty( $kunde ) ) {
			
			$html .= $kunde->vorname;
		}

		

		return $html;
	}

	
	public
	static function shortcodeKundeNachname(
		$attr
	) {
		
		$html = '';

		
		if ( ( ! empty( $attr['pseudo-login'] ) ) && ( $attr['pseudo-login'] == 1 ) ) {
			$pseudo = true;
		} else {
			$pseudo = false;
		}

		$kunde = Auth::getLoggedInKunde( $pseudo );

		if ( ! empty( $kunde ) ) {
			
			$html .= $kunde->nachname;
		}

		

		return $html;
	}

	
	public
	static function shortcodeAenderePasswort() {
		$html = '';

		
		$kunde = Auth::getLoggedInKunde( false );

		if ( empty( $kunde ) ) {
			
			return $html;
		}

		
		$var['passwort-alt'] = '';
		$var['passwort-1']   = '';
		$var['passwort-2']   = '';

		
		if ( filter_input( INPUT_POST, 'muv-kk-passwort-alt' ) !== null ) {
			
			$var['passwort-alt'] = filter_input( INPUT_POST, 'muv-kk-passwort-alt' );
			$var['passwort-1']   = filter_input( INPUT_POST, 'muv-kk-passwort-1' );
			$var['passwort-2']   = filter_input( INPUT_POST, 'muv-kk-passwort-2' );

			$res = $kunde->aenderePwd( $var['passwort-alt'], $var['passwort-1'], $var['passwort-2'] );

			if ( $res !== true ) {
				switch ( $res ) {
					case - 1:
						Flash::addMessage( __( 'Bitte geben Sie Ihr altes Passwort ein.', 'muv-kundenkonto' ), 'danger' );
						Flash::setValue( 'muv-kk.kunde.aendere-pwt.passwort-alt.error', 'has-error' );
						break;
					case - 2:
						Flash::addMessage( __( 'Dies ist nicht Ihr altes Passwort. Bitte geben Sie Ihr altes Passwort ein.', 'muv-kundenkonto' ), 'danger' );
						Flash::setValue( 'muv-kk.kunde.aendere-pwt.passwort-alt.error', 'has-error' );
						break;
					case - 3:
						Flash::addMessage( __( 'Bitte geben Sie ein mindestens 8 stelliges neues Passwort ein.', 'muv-kundenkonto' ), 'danger' );
						Flash::setValue( 'muv-kk.kunde.aendere-pwt.passwort-1.error', 'has-error' );
						break;
					case - 4:
						Flash::addMessage( __( 'Ihr neues Passwort stimmt nicht mit seiner Bestätigung überein.', 'muv-kundenkonto' ), 'danger' );
						Flash::setValue( 'muv-kk.kunde.aendere-pwt.passwort-1.error', 'has-error' );
						Flash::setValue( 'muv-kk.kunde.aendere-pwt.passwort-2.error', 'has-error' );
						break;
				}
			} else {
								Flash::addMessage( __( 'Ihr Passwort wurde erfolgreich geändert.', 'muv-kundenkonto' ), 'success' );
				
				$var['passwort-alt'] = '';
				$var['passwort-1']   = '';
				$var['passwort-2']   = '';
			}
		}
		$html .= Tools::getTemplatePartContent( 'Frontend/Auth/aendere-pwt.tpl.php', $var );

		return $html;

	}

	
	public
	static function shortcodeAendereEmail() {
		$html = '';

		
		$var['email-neu'] = '';
		$var['passwort']  = '';


		
		$kunde = Auth::getLoggedInKunde( false );

		if ( empty( $kunde ) ) {
			
			return $html;
		}

		
		if ( filter_input( INPUT_POST, 'muv-kk-email-neu' ) !== null ) {
			
						$var['email-neu'] = filter_input( INPUT_POST, 'muv-kk-email-neu', FILTER_VALIDATE_EMAIL );
						$var['passwort'] = filter_input( INPUT_POST, 'muv-kk-passwort' );


			$res = $kunde->aendereEmail( $var['passwort'], $var['email-neu'] );

			if ( $res !== true ) {
				switch ( $res ) {
					case - 1:
						Flash::addMessage( __( 'Bitte geben Sie Ihr Passwort zur Autorisierung ein.', 'muv-kundenkonto' ), 'danger' );
						Flash::setValue( 'muv-kk.kunde.aendere-email.passwort.error', 'has-error' );
						break;
					case - 2:
						Flash::addMessage( __( 'Das eingegebenen Passwort ist nicht ihr aktuelles Passwort! Bitte geben Sie Ihr aktuelles Passwort ein.', 'muv-kundenkonto' ), 'danger' );
						Flash::setValue( 'muv-kk.kunde.aendere-email.passwort.error', 'has-error' );
						break;
					case - 3:
						Flash::addMessage( __( 'Bitte geben Sie eine gültige E-Mail Adresse ein.', 'muv-kundenkonto' ), 'danger' );
						Flash::setValue( 'muv-kk.kunde.aendere-email.email-neu.error', 'has-error' );
						break;
					case - 4:
						Flash::addMessage( __( 'Ihre neue und Ihre alte E-Mail Adresse sind gleich. Es wurde nichts geändert.', 'muv-kundenkonto' ), 'info' );
						break;
				}
			} else {
								Flash::addMessage( __( 'Sie haben es fast geschafft! Wir haben Ihnen gerade eben eine E-Mail gesendet. Bitte klicken Sie auf den darin enthaltenen Link, um Ihre neue E-Mail Adresse zu aktivieren.', 'muv-kundenkonto' ), 'info' );
				
				$var['email-neu'] = '';
				$var['passwort']  = '';
			}
		}

		$html .= Tools::getTemplatePartContent( 'Frontend/Auth/aendere-email.tpl.php', $var );

		return $html;

	}

	
	public
	static function shortcodeLoescheKonto() {
		$html = '';

		
		$var['passwort'] = '';

		
		$kunde = Auth::getLoggedInKunde( false );

		if ( empty( $kunde ) ) {
			
			return $html;
		}


		
		if ( filter_input( INPUT_POST, 'muv-kk-passwort' ) !== null ) {
			

						$var['passwort'] = filter_input( INPUT_POST, 'muv-kk-passwort' );


			$res = $kunde->loescheKonto( $var['passwort'] );

			if ( $res !== true ) {
				switch ( $res ) {
					case - 1:
						Flash::addMessage( __( 'Bitte geben Sie Ihr Passwort zur Autorisierung ein.', 'muv-kundenkonto' ), 'danger' );
						Flash::setValue( 'muv-kk.kunde.aendere-email.passwort.error', 'has-error' );
						break;
					case - 2:
						Flash::addMessage( __( 'Das eingegebenen Passwort ist nicht ihr aktuelles Passwort! Bitte geben Sie Ihr aktuelles Passwort ein.', 'muv-kundenkonto' ), 'danger' );
						Flash::setValue( 'muv-kk.kunde.aendere-email.passwort.error', 'has-error' );
						break;
				}
			} else {
				Flash::addMessage( __( 'Schade, dass Sie uns verlassen möchten! Aus Sicherheitsgründen haben wir Ihnen gerade eben eine E-Mail gesendet. Bitte klicken Sie auf den darin enthaltenen Link, um Ihr Konto unwiderbringlich zu löschen.', 'muv-kundenkonto' ), 'info' );
			}
		}

		$html .= Tools::getTemplatePartContent( 'Frontend/Auth/loesche-konto.tpl.php', $var );

		return $html;
	}
}
