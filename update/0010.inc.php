<?php


defined( 'ABSPATH' ) OR exit;




$sql = "ALTER TABLE " . $tables['kunden'] . "
	ADD COLUMN `konto_loeschen_token` VARCHAR(128) NOT NULL DEFAULT '' COMMENT 'Zum Autorisieren der Konto-Löschung' AFTER `passwort_geaendert_am_utc`,
	ADD INDEX `K__konto_loeschen_token` (`konto_loeschen_token`);";
$wpdb->query( $sql );
