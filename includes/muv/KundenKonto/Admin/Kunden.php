<?php

namespace muv\KundenKonto\Admin;

use muv\KundenKonto\Classes\DB;


defined( 'ABSPATH' ) OR exit;


class Kunden {

	
	public static function init() {
		
	}

	
	public static function handleKunden() {
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Sie haben nicht das Recht, diese Seite zu sehen!', 'muv-kundenkonto' ) );
		}

		echo '<div class="wrap muv muv-kundenkonto">';
		echo '<h1><i class="fa fa-fw fa-users"></i>' . __( 'Kunden', 'muv-kundenkonto' ) . '</h1>';

		settings_errors();

		
		$activeTab = filter_input( INPUT_GET, 'tab' );
		if ( empty( $activeTab ) ) {
			$activeTab = 'aktiviert';
		}
		

		$db = new DB();

		$sqlAktiviert      = "SELECT count(*) FROM " . $db->tbl( 'kunden' ) . " WHERE email_verifiziert_am IS NOT NULL";
		$sqlNichtAktiviert = "SELECT count(*) FROM " . $db->tbl( 'kunden' ) . " WHERE email_verifiziert_am IS NULL";

		$anzAktiviert      = (int) $db->getOne( $sqlAktiviert );
		$anzNichtAktiviert = (int) $db->getOne( $sqlNichtAktiviert );

		echo '<h2 class="nav-tab-wrapper">';
		echo '<a href="?page=muv-kk-kunden&tab=aktiviert" class="nav-tab ';
		if ( $activeTab === 'aktiviert' ) {
			echo 'nav-tab-active';
		}
		echo '"><i class="fa fa-fw fa-user"></i>' . __( 'Aktiviert', 'muv-kundenkonto' );
		if ( $anzAktiviert > 0 ) {
			echo ' (' . $anzAktiviert . ')';
		}
		echo '</a>';
		echo '<a href="?page=muv-kk-kunden&tab=nicht-aktiviert" class="nav-tab ';
		if ( $activeTab === 'nicht-aktiviert' ) {
			echo 'nav-tab-active';
		}
		echo '"><i class="fa fa-fw fa-user-o"></i>' . __( 'Nicht aktiviert', 'muv-kundenkonto' );
		if ( $anzNichtAktiviert > 0 ) {
			echo ' (' . $anzNichtAktiviert . ')';
		}
		echo '</a>';

		echo '</h2>';

		
		switch ( $activeTab ) {
			case 'aktiviert':
				self::zeigeAktivierteKunden();
				break;
			case 'nicht-aktiviert':
				self::zeigeNichtAktivierteKunden();
				break;
		}

		echo '</div>';
	}

	
	public static function zeigeAktivierteKunden() {
		self::zeigeKundenTable( 'aktiviert' );
	}

	
	public static function zeigeNichtAktivierteKunden() {
		self::zeigeKundenTable( 'nicht-aktiviert' );
	}

	
	private static function zeigeKundenTable( $status ) {
		$dateFormat = get_option( 'date_format' );
		$timeFormat = get_option( 'time_format' );
		$format     = $dateFormat . '  (' . $timeFormat . ')';

		$db = new DB();

		$sql = "SELECT id, email, vorname, kundennr, erstellt_am, letzter_login FROM " . $db->tbl( 'kunden' );
		if ( $status === 'aktiviert' ) {
			$sql .= " WHERE email_verifiziert_am IS NOT NULL";
		}
		if ( $status === 'nicht-aktiviert' ) {
			$sql .= " WHERE email_verifiziert_am IS NULL";
		}

		$kunden = $db->getAll( $sql );

		if ( ! empty( $kunden ) ) {
			if ( $status === 'aktiviert' ) {
				echo '<br>';
				_e( 'Die folgende Tabelle zeigt alle Kunden, die Ihre E-Mail Adresse bestätigt und damit Ihr Kundenkonto ' .
				    'aktiviert haben.', 'muv-kundenkonto' );
				echo '<br><br>';
			}
			if ( $status === 'nicht-aktiviert' ) {
				echo '<br>';
				_e( 'Die folgende Tabelle zeigt alle Kunden, die ein Kundenkonto angelegt, es aber noch nicht bestätigt ' .
				    'bzw. aktiviert haben.', 'muv-kundenkonto' );
				echo '<br><br>';
			}

			echo '<table  class="datatable display" cellspacing="0" width="100%">
	    <thead>
		<tr>
		    <th>' . __( 'E-Mail', 'muv-kundenkonto' ) . '</th>
		    <th>' . __( 'Name', 'muv-kundenkonto' ) . '</th>
		    <th>' . __( 'Kunden-Nr.', 'muv-kundenkonto' ) . '</th>
		    <th>' . __( 'Konto erstellt am', 'muv-kundenkonto' ) . '</th>
		    <th>' . __( 'Letzter Login', 'muv-kundenkonto' ) . '</th>
		</tr>
	    </thead>
	    <tbody>';
			foreach ( $kunden as $kunde ) {
				echo '<tr>';
				echo '<td>' . esc_html( $kunde['email'] ) . '</td>';
				echo '<td>';
				if ( ! empty( $kunde['vorname'] ) ) {
					echo esc_html( $kunde['vorname'] ) . ' ';
				}
				if ( ! empty( $kunde['nachname'] ) ) {
					echo esc_html( $kunde['nachname'] ) . ' ';
				}
				echo '</td>';
				echo '<td>';
				if ( ! empty( $kunde['kundennr'] ) ) {
					echo esc_html( $kunde['kundennr'] ) . ' ';
				}
				echo '</td>';
				echo '<td>' . date_i18n( $format, strtotime( $kunde['erstellt_am'] ) ) . '</td>';
				echo '<td>';
				if ( empty( $kunde['letzter_login'] ) ) {
					echo '---';
				} else {
					echo date_i18n( $format, strtotime( $kunde['letzter_login'] ) ) . '</td>';
				}
				echo '</tr>';
			}
			echo '</tbody>
	    </table>';
		} else {
			if ( $status === 'aktiviert' ) {
				echo '<div class="center kein-datensatz">';
				echo '<i class="fa fa-user"></i><br><br><br>';
				echo __( 'Es gibt aktuell keine Kunden, die Ihre E-Mail Adresse bestätigt und damit Ihr Kundenkonto ' .
				         'aktiviert haben.', 'muv-kundenkonto' );
				echo '</div>';
			}
			if ( $status === 'nicht-aktiviert' ) {
				echo '<div class="center kein-datensatz">';
				echo '<i class="fa fa-user"></i><br><br><br>';
				echo __( 'Es gibt aktuell keine Kunden, die ein Kundenkonto angelegt, es aber noch nicht bestätigt ' .
				         'bzw. aktiviert haben.', 'muv-kundenkonto' );
				echo '</div>';
			}
		}
	}

}
