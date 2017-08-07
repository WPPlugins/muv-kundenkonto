<?php

namespace muv\KundenKonto\Classes;



defined( 'ABSPATH' ) OR exit;


class Auth {

	
	public static function getLoggedInKunde( $pseudo = false ) {
		
		if ( $pseudo ) {
			$pseudo = get_option( 'muv-kk-erlaube-pseudo-login', DefaultSettings::ERLAUBE_PSEUDO_LOGIN );
		}

		
		if ( $pseudo == true ) {
			
			$pseudoLoginToken = filter_input( INPUT_COOKIE, 'muv-kk-plogin-token' );

			
			if ( empty( $pseudoLoginToken ) ) {
				return null; 			}

			
			$db = new DB();

			$sql = "SELECT id FROM " . $db->tbl( 'kunden' ) . " WHERE pseudo_login_token = ? LIMIT 1";
			$id  = $db->getOne( $sql, $pseudoLoginToken );

			if ( empty( $id ) ) {
				return null; 			}

			

			return new Kunde( $id );

		} else {

			
			$loginToken = filter_input( INPUT_COOKIE, 'muv-kk-login-token' );

			
			if ( empty( $loginToken ) ) {
				return null; 			}

			
			$db = new DB();

			$sql = "SELECT id FROM " . $db->tbl( 'kunden' ) . " WHERE login_token = ? LIMIT 1";
			$id  = $db->getOne( $sql, $loginToken );

			if ( empty( $id ) ) {
				return null; 			}

						$kunde = new Kunde( $id );

			
			$optionen = get_option( 'muv-kk-logout', array() );
			$idle     = abs( (int) $optionen['idle'] );
			$gesamt   = abs( (int) $optionen['gesamt'] );

			
			$timeLocal = current_time( 'mysql', false );
			$timeUtc   = current_time( 'mysql', true );

			
			$inaktiv = strtotime( $timeUtc ) - strtotime( $kunde->letzte_aktivitaet_utc );
			if ( $inaktiv > 60 * $idle ) { 				Flash::addMessage( __( 'Sie waren zu lange inaktiv. Ihre Sitzung wurde aus Sicherheitsgründen beendet.', 'muv-kundenkonto' ), 'warning' );
				
				self::logout( $kunde->id, false );

				return null; 			}

			
			$loginTime = strtotime( $timeUtc ) - strtotime( $kunde->letzter_login_utc );
			if ( $loginTime > 60 * 60 * $gesamt ) { 				Flash::addMessage( __( 'Sie waren zu lange angemeldet. Ihre Sitzung wurde aus Sicherheitsgründen beendet.', 'muv-kundenkonto' ), 'warning' );
				
				self::logout( $kunde->id, false );

				return null; 			}

			
			if ( $inaktiv > 30 ) { 				$kunde->letzte_aktivitaet     = $timeLocal;
				$kunde->letzte_aktivitaet_utc = $timeUtc;
			}

			

			return $kunde;
		}
	}

	
	public static function getKundeMitEMail( $email ) {
		$db = new DB();

		$sql = "SELECT id FROM " . $db->tbl( 'kunden' ) . " WHERE email = ? LIMIT 1";
		$id  = $db->getOne( $sql, $email );

		if ( empty( $id ) ) {
			return null; 		}

				return new Kunde( $id );
	}

	
	public static function checkLogin( $email, $pwd ) {
		
		if ( empty( $email ) ) {
			return - 1; 		}
		if ( empty( $pwd ) ) {
			return - 2; 		}

		
		$db = new DB();

		$sql   = "SELECT * FROM " . $db->tbl( 'kunden' ) . " WHERE email = ? LIMIT 1";
		$daten = $db->getRow( $sql, $email );

		
		if ( empty( $daten ) ) {
			sleep( 5 ); 
			return - 3; 		}

		
		if ( empty( $daten['email_verifiziert_am'] ) ) {
			
			self::sendeBestaetigungsEmail( $daten['email'] );

			return - 4;
		}

		
		$valid = password_verify( $pwd, $daten['passwort'] );
		if ( ! $valid ) {
			sleep( 5 ); 
			return - 3; 		}

		
		self::doLogin( $daten['id'] );

		

		return true;
	}

	
	private static function doLogin( $kundeId ) {
		
		session_regenerate_id();

		$db = new DB();

		


		
		$param[':login_token'] = bin2hex( openssl_random_pseudo_bytes( 100 ) );

		
		$param[':time_local'] = current_time( 'mysql', false );
		$param[':time_utc']   = current_time( 'mysql', true );

		$param[':id'] = $kundeId;

		$sql = "UPDATE " . $db->tbl( 'kunden' ) . " SET " .
		       " letzter_login = :time_local, " .
		       " letzter_login_utc = :time_utc, " .
		       " letzte_aktivitaet = :time_local, " .
		       " letzte_aktivitaet_utc = :time_utc, " .
		       " pwd_token = '', " .
		       " login_token = :login_token " .
		       " WHERE id = :id";
		$db->exec( $sql, $param );

		
		$loginDomain = parse_url( 'http://' . get_option( 'muv-kk-login-domain', '' ), PHP_URL_HOST );

		if ( empty( $loginDomain ) ) {
			$loginDomain = ''; 		}

		setcookie( 'muv-kk-login-token', $param[':login_token'], 0, '/', $loginDomain );

		
		$pseudoLogin = get_option( 'muv-kk-erlaube-pseudo-login', DefaultSettings::ERLAUBE_PSEUDO_LOGIN );
		if ( $pseudoLogin == true ) {
			
			$sql   = "SELECT pseudo_login_token FROM " . $db->tbl( 'kunden' ) . " WHERE id = ?";
			$token = $db->getOne( $sql, $kundeId );
			if ( empty( $token ) ) {
				
				unset( $param );
				$param[':id']                 = $kundeId;
				$token                        = bin2hex( openssl_random_pseudo_bytes( 16 ) );
				$param[':pseudo_login_token'] = $token;
				$sql                          = "UPDATE " . $db->tbl( 'kunden' ) . " SET " .
				                                " pseudo_login_token = :pseudo_login_token " .
				                                " WHERE id = :id";
				$db->exec( $sql, $param );
			}

			
			setcookie( 'muv-kk-plogin-token', $token, time() + 60 * 60 * 24 * 365, '/', $loginDomain );
		}
	}

	
	public static function logout( $kundeId, $clearPseudoLogin = true ) {
		$db = new DB();

		
		$sql = "UPDATE " . $db->tbl( 'kunden' ) . " SET " .
		       " login_token = NULL " .
		       " WHERE id = ?";
		$db->exec( $sql, $kundeId );

		
		if ( $clearPseudoLogin ) {
			$sql = "UPDATE " . $db->tbl( 'kunden' ) . " SET " .
			       " pseudo_login_token = NULL " .
			       " WHERE id = ?";
			$db->exec( $sql, $kundeId );
		}

		
	}

	
	public static function erzeugeKundenZugang( $email, $vorname, $nachname, $passwort, $verifiziert = false ) {

		
		$email = filter_var( $email, FILTER_VALIDATE_EMAIL );
		if ( empty( $email ) ) {
			return - 1;
		}
		if ( empty( $passwort ) || ( strlen( $passwort ) < 8 ) ) {
			return - 2;
		}


		$db = new DB();

		$sql                = "SELECT email_verifiziert_am FROM " . $db->tbl( 'kunden' ) . " WHERE email = ? LIMIT 1";
		$emailVerifiziertAm = $db->getOne( $sql, $email );


		
		if ( ! empty( $emailVerifiziertAm ) ) {
			
			if ( empty( $emailVerifiziertAm ) ) {
				self::sendeBestaetigungsEmail( $email );

				return - 3;
			} else {
				return - 4;
			}
		}

		
		$param[':email']       = $email;
		$param[':vorname']     = empty( $vorname ) ? '' : $vorname;
		$param[':nachname']    = empty( $nachname ) ? '' : $nachname;
		$param[':passwort']    = password_hash( $passwort, PASSWORD_DEFAULT );
		$param[':erstellt_ip'] = filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP );
		
		if ( empty( $param[':erstellt_ip'] ) ) {
			$param[':erstellt_ip'] = '???.???.???.???';
		}

		
		$param[':email_neu'] = $email;

		
		$param[':time_local'] = current_time( 'mysql', false );
		$param[':time_utc']   = current_time( 'mysql', true );

		
		$sql = "INSERT INTO " . $db->tbl( 'kunden' ) .
		       "(`email`, `passwort`, `vorname`, `nachname`, `erstellt_am`, `erstellt_am_utc`, `erstellt_ip`, `email_neu`) " .
		       "VALUES (:email, :passwort, :vorname, :nachname, :time_local, :time_utc, :erstellt_ip, :email_neu)";
		$db->exec( $sql, $param );

		if ( ! $verifiziert ) {
			
			self::sendeBestaetigungsEmail( $email );
		}

		return true; 	}

	
	private static function sendeBestaetigungsEmail( $email ) {
		$db = new DB();

		$sql   = "SELECT * FROM " . $db->tbl( 'kunden' ) . " WHERE email = ? LIMIT 1";
		$kunde = $db->getRow( $sql, $email );

		if ( empty( $kunde ) ) {
			return - 101; 		}

		
		if ( empty( $kunde['email_token'] ) ) {
			$kunde['email_token'] = bin2hex( openssl_random_pseudo_bytes( 50 ) );
			$sql                  = "UPDATE " . $db->tbl( 'kunden' ) . " SET email_token = ? WHERE id = ?";
			$db->exec( $sql, [ 1 => $kunde['email_token'], 2 => $kunde['id'] ] );
		}

		
		$fromMail                 = sanitize_email( get_option( 'muv-kk-email-von-mail' ) );
		$fromName                 = sanitize_text_field( get_option( 'muv-kk-email-von-name' ) );
		$subject                  = sanitize_text_field( get_option( 'muv-kk-email-vorlage-konto-aktivieren-betreff' ) );
		$daten['html-content']    = get_option( 'muv-kk-email-vorlage-konto-aktivieren-html' );
		$daten['text-content']    = sanitize_textarea_field( get_option( 'muv-kk-email-vorlage-konto-aktivieren-text' ) );
		$img['email-logo']        = trim( get_option( 'muv-kk-email-vorlage-logo', '' ) );
		$daten['has-header-logo'] = ! empty( $img['email-logo'] );

		
		
		$goto = filter_input( INPUT_POST, 'muv-kk-seite', FILTER_SANITIZE_URL );
		if ( empty( $goto ) ) {
			$goto = Tools::getPageUrl();
		}

		$link = add_query_arg( [
			'muv-kk-aktion' => 'aktiviere-konto',
			'muv-kk-email'    => $email,
			'muv-kk-token'    => $kunde['email_token']
		], $goto );

		
		$jahr = date( 'Y' );
		
		$name = trim( $kunde['vorname'] . ' ' . $kunde['nachname'] );

		$daten['html-content'] = str_replace( '##LINK##', $link, $daten['html-content'] );
		$daten['html-content'] = str_replace( '##JAHR##', $jahr, $daten['html-content'] );

		
		if ( $name === '' ) {
			$daten['html-content'] = str_replace( ' ##NAME##', '', $daten['html-content'] );
		}
		$daten['html-content'] = str_replace( '##NAME##', $name, $daten['html-content'] );
		$daten['html-content'] = str_replace( '##EMAIL-TO##', $fromMail, $daten['html-content'] );

		$daten['text-content'] = str_replace( '##LINK##', $link, $daten['text-content'] );
		$daten['text-content'] = str_replace( '##JAHR##', $jahr, $daten['text-content'] );
		if ( $name === '' ) {
			$daten['text-content'] = str_replace( ' ##NAME##', '', $daten['text-content'] );
		}
		$daten['text-content'] = str_replace( '##NAME##', $name, $daten['text-content'] );
		$daten['text-content'] = str_replace( '##EMAIL-TO##', $fromMail, $daten['text-content'] );

		$htmlMessage  = Tools::getTemplateContent( 'Mails/html.tpl.php', $daten );
		$plainMessage = Tools::getTemplateContent( 'Mails/text.tpl.php', $daten );

		
		$typ = (int) ( get_option( 'muv-kk-email-vorlage-konto-aktivieren-typ', '' ) );

		if ( $typ === 1 ) {
			
			$htmlMessage = '';
			$img         = array();
		}
		if ( $typ === 2 ) {
			
			$plainMessage = '';
		}

		return Mail::send( $fromMail, $fromName, $email, $subject, $htmlMessage, $plainMessage, '', $img );
	}

	
	public static function bestaetigeEmail( $email, $emailToken ) {
		$db = new DB();

		$sql   = "SELECT * FROM " . $db->tbl( 'kunden' ) . " WHERE email = ? LIMIT 1";
		$daten = $db->getRow( $sql, $email );

		if ( empty( $daten ) ) {
			return - 1; 		}

		
		if ( ( $daten['email_token'] == $emailToken ) && ( empty( $daten['email_verifiziert_am'] ) ) ) {
			$sql = "UPDATE " . $db->tbl( 'kunden' ) . " SET " .
			       "email = email_neu, " .
			       "email_verifiziert_am = :time_local, " .
			       "email_verifiziert_am_utc = :time_utc, " .
			       "email_neu = '', " .
			       "email_token = '' " .
			       "WHERE id = :id";
			
			$param[':time_local'] = current_time( 'mysql', false );
			$param[':time_utc']   = current_time( 'mysql', true );
			$param[':id']         = $daten['id'];

			$db->exec( $sql, $param );

			return true; 		} else {
			
			if ( empty( $daten['email_verifiziert_am'] ) ) {
				return - 2; 			}
			if ( $daten['email_token'] != $emailToken ) {
				return - 1; 			}
		}

		return true;
	}

	
	public static function sendePwdVergessenEmail( $email ) {
		
		$email = filter_var( $email, FILTER_VALIDATE_EMAIL );
		if ( empty( $email ) ) {
			return - 1; 		}

		$db = new DB();

		$sql   = "SELECT * FROM " . $db->tbl( 'kunden' ) . " WHERE email = ? LIMIT 1";
		$kunde = $db->getRow( $sql, $email );

		if ( empty( $kunde ) ) {
			return - 2; 		}


		if ( empty( $kunde['email_verifiziert_am'] ) ) {
			self::sendeBestaetigungsEmail( $kunde['email'] );

			return - 3; 		}

		
		$kunde['pwd_token'] = bin2hex( openssl_random_pseudo_bytes( 50 ) );
		$sql                = "UPDATE " . $db->tbl( 'kunden' ) . " SET pwd_token = ? WHERE id = ?";
		$db->exec( $sql, [ 1 => $kunde['pwd_token'], 2 => $kunde['id'] ] );

		
		$fromMail                 = sanitize_email( get_option( 'muv-kk-email-von-mail' ) );
		$fromName                 = sanitize_text_field( get_option( 'muv-kk-email-von-name' ) );
		$subject                  = sanitize_text_field( get_option( 'muv-kk-email-vorlage-pwd-vergessen-betreff' ) );
		$daten['html-content']    = get_option( 'muv-kk-email-vorlage-pwd-vergessen-html' );
		$daten['text-content']    = sanitize_textarea_field( get_option( 'muv-kk-email-vorlage-pwd-vergessen-text' ) );
		$img['email-logo']        = trim( get_option( 'muv-kk-email-vorlage-logo', '' ) );
		$daten['has-header-logo'] = empty( $img['email-logo'] );

		
		
		$link = add_query_arg( [
			'muv-kk-aktion' => 'aendere-vergessenes-pwt',
			'muv-kk-email'    => $email,
			'muv-kk-token'    => $kunde['pwd_token']
		], Tools::getPageUrl() );

		
		$jahr = date( 'Y' );
		
		$name = trim( $kunde['vorname'] . ' ' . $kunde['nachname'] );

		$daten['html-content'] = str_replace( '##LINK##', $link, $daten['html-content'] );
		$daten['html-content'] = str_replace( '##JAHR##', $jahr, $daten['html-content'] );

		
		if ( $name === '' ) {
			$daten['html-content'] = str_replace( ' ##NAME##', '', $daten['html-content'] );
		}
		$daten['html-content'] = str_replace( '##NAME##', $name, $daten['html-content'] );
		$daten['html-content'] = str_replace( '##EMAIL-TO##', $fromMail, $daten['html-content'] );

		$daten['text-content'] = str_replace( '##LINK##', $link, $daten['text-content'] );
		$daten['text-content'] = str_replace( '##JAHR##', $jahr, $daten['text-content'] );
		if ( $name === '' ) {
			$daten['text-content'] = str_replace( ' ##NAME##', '', $daten['text-content'] );
		}
		$daten['text-content'] = str_replace( '##NAME##', $name, $daten['text-content'] );
		$daten['text-content'] = str_replace( '##EMAIL-TO##', $fromMail, $daten['text-content'] );

		$htmlMessage  = Tools::getTemplateContent( 'Mails/html.tpl.php', $daten );
		$plainMessage = Tools::getTemplateContent( 'Mails/text.tpl.php', $daten );

		
		$typ = (int) ( get_option( 'muv-kk-email-vorlage-pwd-vergessen-typ', '' ) );

		if ( $typ === 1 ) {
			
			$htmlMessage = '';
			$img         = array();
		}
		if ( $typ === 2 ) {
			
			$plainMessage = '';
		}

		return Mail::send( $fromMail, $fromName, $email, $subject, $htmlMessage, $plainMessage, '', $img );
	}

	
	public static function aendereVegessenesPwd( $email, $token, $pwd1, $pwd2 ) {

		if ( empty( $pwd1 ) || ( strlen( $pwd1 ) < 8 ) ) {
			return - 1; 		}

		if ( $pwd1 !== $pwd2 ) {
			return - 2; 		}
		if ( trim( $email ) == '' ) {
			return - 3; 		}
		if ( trim( $token ) == '' ) {
			return - 3; 		}

		
		$db = new DB();

		$sql      = "SELECT id FROM " . $db->tbl( 'kunden' ) . " WHERE email = ? AND  pwd_token = ? LIMIT 1";
		$kundenId = $db->getOne( $sql, [ 1 => $email, 2 => $token ] );

		if ( empty( $kundenId ) ) {
			return - 3; 		}

		
		$sql = "UPDATE " . $db->tbl( 'kunden' ) . " SET letztes_passwort = passwort WHERE id = ?";
		$db->exec( $sql, $kundenId );

		$sql                  = "UPDATE " . $db->tbl( 'kunden' ) . " SET " .
		                        "passwort = :passwort, " .
		                        "passwort_geaendert_am = :time_local, " .
		                        "passwort_geaendert_am_utc = :time_utc " .
		                        "WHERE id = ?";
		$param[':passwort']   = password_hash( $pwd1, PASSWORD_DEFAULT );
		$param[':time_local'] = current_time( 'mysql', false );
		$param[':time_utc']   = current_time( 'mysql', true );
		$param[':id']         = $kundenId;
		$db->exec( $sql, $param );

		return true;
	}

	
	public static function loescheKonto( $email, $token ) {
		$token = trim( $token );
		$email = trim( $email );
		if ( empty( $token )  ||  empty( $email ) ) {
			return - 1; 		}

		
		$db = new DB();

		$sql      = "SELECT id FROM " . $db->tbl( 'kunden' ) . " WHERE email = ? AND konto_loeschen_token = ? LIMIT 1";
		$kundenId = $db->getOne( $sql, [1 => $email, 2 => $token] );

		if ( empty( $kundenId ) ) {
			return - 1; 		}

		
		$sql = "DELETE FROM " . $db->tbl( 'kunden' ) . " WHERE id = ?";
		$db->exec( $sql, $kundenId );

		
		$sql = "DELETE FROM " . $db->tbl( 'kundendaten' ) . " WHERE kunde_id = ?";
		$db->exec( $sql, $kundenId );
		$sql = "DELETE FROM " . $db->tbl( 'kundendaten_ext' ) . " WHERE kunde_id = ?";
		$db->exec( $sql, $kundenId );

		
		do_action( 'muv-kk-auth-konto-loeschen', $kundenId );

		return true;
	}

}
