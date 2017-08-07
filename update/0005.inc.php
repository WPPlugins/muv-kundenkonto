<?php


defined( 'ABSPATH' ) OR exit;




$sql = "ALTER TABLE " . $tables['kundendaten'] . " COMMENT='Die erweiterten Zusatzinformationen zum einzelnen Kunden';";
$wpdb->query( $sql );

$sql = "RENAME TABLE " . $tables['kundendaten'] . " TO " . $tables['kundendaten_ext'];
$wpdb->query( $sql );


$sql = "CREATE TABLE " . $tables['kundendaten'] . " (
	`user_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Die id des Kunden, zu dem die Daten gehören',
	`key1` VARCHAR(50) NOT NULL COMMENT 'Die \"Kennung\" der Daten',
	`daten` LONGTEXT NULL COMMENT 'Die zu speichernden Daten',
	PRIMARY KEY (`user_id`, `key1`)
)
COMMENT='Die Zusatzinformationen zum einzelnen Kunden'
";

$wpdb->query( $sql );


$sql = "ALTER TABLE " . $tables['kunden'] . "
	CHANGE COLUMN `password_geaendert_am` `passwort_geaendert_am` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Wann wurde das Pwd zum letzten mal geändert' AFTER `letztes_passwort`,
	CHANGE COLUMN `password_geaendert_am_utc` `passwort_geaendert_am_utc` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Wann wurde das Pwd zum letzten mal geändert' AFTER `passwort_geaendert_am`;
";
$wpdb->query( $sql );

