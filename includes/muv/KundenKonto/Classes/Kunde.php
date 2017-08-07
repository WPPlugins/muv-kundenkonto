<?php

namespace muv\KundenKonto\Classes;


defined( 'ABSPATH' ) OR exit;


class Kunde extends \stdClass {
	
	private $tableDaten = [];
	
	private $zusatzDaten = [];
	
	private $idDB;

	
	function __construct( $id ) {
		$this->idDB = $id;

		$db = new DB();

		
		$sql              = "SELECT * FROM " . $db->tbl( 'kunden' ) . " WHERE id = ? LIMIT 1";
		$this->tableDaten = $db->getRow( $sql, $this->idDB );

		
		$sql         = "SELECT key1, daten FROM " . $db->tbl( 'kundendaten' ) . " WHERE kunde_id = ?";
		$zusatzDaten = $db->getAll( $sql, $this->idDB );
		if ( is_array( $zusatzDaten ) ) {
			foreach ( $zusatzDaten as $d ) {
				$this->zusatzDaten[ $d['key1'] ] = $d['daten'];
			}
		}
	}

	
	public function __get( $name ) {
		
		if ( $name === 'id' ) {
			return $this->idDB;
		}

		
		if ( array_key_exists( $name, $this->tableDaten ) ) {
			return $this->tableDaten[ $name ];
		}

		
		if ( array_key_exists( $name, $this->zusatzDaten ) ) {
			return $this->tableZusatzDaten[ $name ];
		}

				return null;
	}

	
	public function __set( $name, $value ) {
		if ( $name === 'id' ) {
			
			return;
		}

		$db = new DB();

		
		if ( array_key_exists( $name, $this->tableDaten ) ) {
			
			if ( $value === $this->tableDaten[ $name ] ) {
				return;
			}

						$this->tableDaten[ $name ] = $value;

			
			$sql = "UPDATE " . $db->tbl( 'kunden' ) . " SET " . $name . " = ? WHERE id = ?";
			$db->exec( $sql, [ 1 => $value, 2 => $this->idDB ] );

			return;

		}

		
		if ( array_key_exists( $name, $this->zusatzDaten ) ) {
			
			if ( $value === $this->tableZusatzDaten[ $name ] ) {
				return;
			}

						$this->tableZusatzDaten[ $name ] = $value;

			
			$sql                = "UPDATE " . $db->tbl( 'kundendaten' ) . " SET daten = :daten WHERE kunde_id = :kunde_id AND key1 = :key1";
			$param[':daten']    = $value;
			$param[':kunde_id'] = $this->idDB;
			$param[':key1']     = $name;

			$db->exec( $sql, $param );

			return;
		} else {
			
			$sql                = "INSERT INTO " . $db->tbl( 'kundendaten' ) . " (kunde_id, key1, daten) VALUES (:daten, :kunde_id, :key1)";
			$param[':daten']    = $value;
			$param[':kunde_id'] = $this->idDB;
			$param[':key1']     = $name;

			$db->exec( $sql, $param );
		}
	}

	
	private function sendeEmail( $kennung, $zusatzDaten ) {

		
		$fromMail                 = sanitize_email( get_option( 'muv-kk-email-von-mail' ) );
		$fromName                 = sanitize_text_field( get_option( 'muv-kk-email-von-name' ) );
		$subject                  = sanitize_text_field( get_option( 'muv-kk-email-vorlage-' . $kennung . '-betreff' ) );
		$daten['html-content']    = get_option( 'muv-kk-email-vorlage-' . $kennung . '-html' );
		$daten['text-content']    = sanitize_textarea_field( get_option( 'muv-kk-email-vorlage-' . $kennung . '-text' ) );
		$img['email-logo']        = trim( get_option( 'muv-kk-email-vorlage-logo', '' ) );
		$daten['has-header-logo'] = ! empty( $img['email-logo'] );

		
				$jahr = date( 'Y' );
				$name = trim( $this->vorname . ' ' . $this->nachname );

		$daten['html-content'] = str_replace( '##JAHR##', $jahr, $daten['html-content'] );

		
		if ( $name === '' ) {
			$daten['html-content'] = str_replace( ' ##NAME##', '', $daten['html-content'] );
		}
		$daten['html-content'] = str_replace( '##NAME##', $name, $daten['html-content'] );
		$daten['html-content'] = str_replace( '##EMAIL-TO##', $fromMail, $daten['html-content'] );

		$daten['text-content'] = str_replace( '##JAHR##', $jahr, $daten['text-content'] );
		if ( $name === '' ) {
			$daten['text-content'] = str_replace( ' ##NAME##', '', $daten['text-content'] );
		}
		$daten['text-content'] = str_replace( '##NAME##', $name, $daten['text-content'] );
		$daten['text-content'] = str_replace( '##EMAIL-TO##', $fromMail, $daten['text-content'] );


		
		if ( is_array( $zusatzDaten ) ) {
			foreach ( $zusatzDaten as $k => $v ) {
				$daten['html-content'] = str_replace( '##' . strtoupper( $k ) . '##', $v, $daten['html-content'] );
				$daten['text-content'] = str_replace( '##' . strtoupper( $k ) . '##', $v, $daten['text-content'] );
			}
		}

		$daten = array_merge( $daten, $zusatzDaten );


		$htmlMessage  = Tools::getTemplateContent( 'Mails/html.tpl.php', $daten );
		$plainMessage = Tools::getTemplateContent( 'Mails/text.tpl.php', $daten );

		
		$typ = (int) ( get_option( 'muv-kk-email-vorlage-' . $kennung . '-typ', '' ) );

		if ( $typ === 1 ) {
			
			$htmlMessage = '';
			$img         = array();
		}
		if ( $typ === 2 ) {
			
			$plainMessage = '';
		}

		return Mail::send( $fromMail, $fromName, $this->email, $subject, $htmlMessage, $plainMessage, '', $img );
	}


	
	public function aenderePwd( $pwdAlt, $pwd1, $pwd2 ) {
		$pwd1   = trim( $pwd1 );
		$pwd2   = trim( $pwd2 );
		$pwdAlt = trim( $pwdAlt );

		
		if ( empty( $pwdAlt ) ) {
			return - 1; 		}
		$valid = password_verify( $pwdAlt, $this->passwort );
		if ( ! $valid ) {
			sleep( 2 ); 			return - 2; 		}

		
		if ( empty( $pwd1 ) || ( strlen( $pwd1 ) < 8 ) ) {
			return - 3; 		}
		if ( $pwd1 !== $pwd2 ) {
			return - 4; 		}

		
		$passwort = password_hash( $pwd1, PASSWORD_DEFAULT );

		$this->letztes_passwort = $this->passwort;
		$this->passwort         = $passwort;
				$timeLocal                       = current_time( 'mysql', false );
		$timeUtc                         = current_time( 'mysql', true );
		$this->passwort_geaendert_am     = $timeLocal;
		$this->passwort_geaendert_am_utc = $timeUtc;

		
		$this->sendeEmailPwdGeaendert();

		return true;
	}

	
	private function sendeEmailPwdGeaendert() {
				$daten = [];

				return $this->sendeEmail( 'pwd-geaendert', $daten );
	}


	
	public function aendereEmail( $pwd, $emailNeu ) {
		$pwd      = trim( $pwd );
		$emailNeu = trim( $emailNeu );

		
		if ( empty( $pwd ) ) {
			return - 1; 		}
		$valid = password_verify( $pwd, $this->passwort );
		if ( ! $valid ) {
			sleep( 2 ); 			return - 2; 		}

		
		$emailNeu = filter_var( trim( $emailNeu ), FILTER_VALIDATE_EMAIL );
		if ( empty( $emailNeu ) ) {
			return - 3; 		}


		
		if ( strtolower( $emailNeu ) === strtolower( $this->email ) ) {
			return - 4; 		}

		
		$this->email_neu   = $emailNeu;
		$this->email_token = bin2hex( openssl_random_pseudo_bytes( 50 ) );

				$this->sendeEmailEmailAktivieren();

		return true; 	}

	
	private function sendeEmailEmailAktivieren() {
		$daten = [];
		$daten['email-neu'] = $this->email_neu;

		
		$daten['link'] = add_query_arg( [
			'muv-kk-aktion' => 'bestaetige-email',
			'muv-kk-email'  => $this->email,
			'muv-kk-token'  => $this->email_token
		], Tools::getPageUrl() );

		return $this->sendeEmail( 'email-aktivieren', $daten );
	}

	
	public function aktiviereNeueEmail( $token ) {
		
		if ( $token !== $this->email_token ) {
			return - 1;
		}

		
		$db  = new DB();
		$sql = "SELECT id FROM " . $db->tbl( 'kunden' ) . " WHERE email = ? LIMIT 1";
		$id  = $db->getOne( $sql, $this->email_neu );
		if ( ! empty( $id ) ) {
						return - 2;
		}


						$this->sendeEmailEmailGeaendert();

		
		$this->email       = $this->email_neu;
		$this->email_neu   = '';
		$this->email_token = '';

				$timeLocal                      = current_time( 'mysql', false );
		$timeUtc                        = current_time( 'mysql', true );
		$this->email_verifiziert_am     = $timeLocal;
		$this->email_verifiziert_am_utc = $timeUtc;

		return true;
	}

	
	private function sendeEmailEmailGeaendert() {
		$daten = [];
		
		$daten['email'] = $this->email_neu;

		return $this->sendeEmail( 'email-geaendert', $daten );
	}


	
	public function logout() {
		Auth::logout( $this->id, true );
	}

	public function loescheKonto( $pwd ) {
		$pwd = trim( $pwd );

		
		if ( empty( $pwd ) ) {
			return - 1; 		}
		$valid = password_verify( $pwd, $this->passwort );
		if ( ! $valid ) {
			sleep( 5 ); 			return - 2; 		}

		
		$this->konto_loeschen_token = bin2hex( openssl_random_pseudo_bytes( 50 ) );

		$this->sendeEmailLoescheKonto();

		return true;
	}

	
	private function sendeEmailLoescheKonto() {
		$daten = [];
		
		$daten['link'] = add_query_arg( [
			'muv-kk-aktion' => 'loesche-konto',
			'muv-kk-email'  => $this->email,
			'muv-kk-token'  => $this->konto_loeschen_token
		], Tools::getPageUrl() );

		return $this->sendeEmail( 'konto-loeschen', $daten );
	}

}